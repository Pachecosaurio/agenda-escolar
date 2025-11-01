<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $status = $request->get('status');
        $category = $request->get('category');
        $search = $request->get('q');
        $from = $request->get('from');
        $to = $request->get('to');

        $query = Payment::forUser($userId)
            ->status($status)
            ->category($category)
            ->betweenDates($from, $to)
            ->search($search)
            ->orderBy('due_date', 'desc');

        $payments = $query->paginate(12)->withQueryString();

        $all = Payment::forUser($userId);
        $now = Carbon::now();

        // Force overdue status update for due_date passed & not paid
        Payment::forUser($userId)
            ->where('status', 'pending')
            ->whereDate('due_date', '<', $now->toDateString())
            ->update(['status' => 'overdue']);

        $stats = [
            'totalPayments' => (clone $all)->count(),
            'totalAmount' => (clone $all)->sum('amount'),
            'paidAmount' => (clone $all)->where('status', 'paid')->sum('amount'),
            'pendingAmount' => (clone $all)->where('status', 'pending')->sum('amount'),
            'overdueAmount' => (clone $all)->where('status', 'overdue')->sum('amount'),
            'paidCount' => (clone $all)->where('status', 'paid')->count(),
            'pendingCount' => (clone $all)->where('status', 'pending')->count(),
            'overdueCount' => (clone $all)->where('status', 'overdue')->count(),
        ];

        // Category distribution
        $categories = Payment::getCategories();
        $categoryDistribution = [];
        foreach ($categories as $key => $label) {
            $catQuery = (clone $all)->where('category', $key);
            $categoryDistribution[$key] = [
                'label' => $label,
                'count' => $catQuery->count(),
                'amount' => $catQuery->sum('amount')
            ];
        }

        return view('payments.index', [
            'payments' => $payments,
            'stats' => $stats,
            'filters' => [
                'status' => $status,
                'category' => $category,
                'q' => $search,
                'from' => $from,
                'to' => $to
            ],
            'categories' => $categories,
            'categoryDistribution' => $categoryDistribution
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Payment::getCategories();
        return view('payments.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:tuition,books,activities,transport,cafeteria,other',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date|after_or_equal:due_date',
            'status' => 'nullable|in:pending,paid,overdue',
            'payment_method' => 'nullable|in:cash,card,transfer,online',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        // Determine status if not provided
        $status = $validated['status'] ?? 'pending';
        if ($status === 'pending' && Carbon::parse($validated['due_date'])->isPast()) {
            $status = 'overdue';
        }
        if ($validated['paid_date'] ?? false) {
            $status = 'paid';
        }

        Payment::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'due_date' => $validated['due_date'],
            'paid_date' => $validated['paid_date'] ?? null,
            'status' => $status,
            'payment_method' => $validated['payment_method'] ?? null,
            'reference' => $validated['reference'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('payments.index')->with('success', 'Pago creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment): View
    {
        $this->authorize('view', $payment);

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment): View
    {
        $this->authorize('update', $payment);

        $categories = Payment::getCategories();
        return view('payments.edit', compact('payment', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $this->authorize('update', $payment);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|in:tuition,books,activities,transport,cafeteria,other',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date|after_or_equal:due_date',
            'status' => 'required|in:pending,paid,overdue',
            'payment_method' => 'nullable|in:cash,card,transfer,online',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $status = $validated['status'];
        if ($status !== 'paid' && ($validated['paid_date'] ?? false)) {
            $status = 'paid';
        } elseif ($status === 'pending' && Carbon::parse($validated['due_date'])->isPast()) {
            $status = 'overdue';
        }

        $payment->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'due_date' => $validated['due_date'],
            'paid_date' => $validated['paid_date'] ?? null,
            'status' => $status,
            'payment_method' => $validated['payment_method'] ?? null,
            'reference' => $validated['reference'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('payments.index')->with('success', 'Pago actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $this->authorize('delete', $payment);

        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Pago eliminado exitosamente.');
    }

    /**
     * Get calendar events for payments
     */
    public function getCalendarEvents(): JsonResponse
    {
        $payments = Payment::where('user_id', Auth::id())->get();

        $events = $payments->map(function (Payment $payment) {
            return [
                'id' => 'payment_' . $payment->id,
                'title' => $payment->title,
                'start' => $payment->due_date ? $payment->due_date->format('Y-m-d') : null,
                'description' => $payment->description,
                'backgroundColor' => $payment->status === 'paid' ? '#28a745' : ($payment->status === 'overdue' ? '#dc3545' : '#ffc107'),
                'borderColor' => $payment->status === 'paid' ? '#28a745' : ($payment->status === 'overdue' ? '#dc3545' : '#ffc107'),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => 'payment',
                    'status' => $payment->status,
                    'amount' => $payment->amount,
                    'category' => $payment->category_text,
                    'description' => $payment->description,
                    'url' => route('payments.show', $payment)
                ]
            ];
        });

        return response()->json($events);
    }
}