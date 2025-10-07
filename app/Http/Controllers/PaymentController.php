<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $payments = Payment::where('user_id', Auth::id())
            ->orderBy('due_date', 'desc')
            ->paginate(10);

        // Calculate statistics from all payments (not just paginated)
        $allPayments = Payment::where('user_id', Auth::id());
        $stats = [
            'totalPayments' => $allPayments->count(),
            'totalAmount' => $allPayments->sum('amount'),
            'paidAmount' => $allPayments->where('status', 'paid')->sum('amount'),
            'pendingAmount' => $allPayments->where('status', 'pending')->sum('amount'),
            'overdueAmount' => $allPayments->where('status', 'overdue')->sum('amount'),
            'paidCount' => $allPayments->where('status', 'paid')->count(),
            'pendingCount' => $allPayments->where('status', 'pending')->count(),
            'overdueCount' => $allPayments->where('status', 'overdue')->count(),
        ];

        return view('payments.index', compact('payments', 'stats'));
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
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'due_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        Payment::create([
            'user_id' => Auth::id(),
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'amount' => $validatedData['amount'],
            'category' => $validatedData['category'],
            'due_date' => $validatedData['due_date'],
            'status' => 'pending',
            'notes' => $validatedData['notes']
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Pago creado exitosamente.');
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

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue',
            'paid_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $payment->update($validatedData);

        return redirect()->route('payments.index')
            ->with('success', 'Pago actualizado exitosamente.');
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
                'backgroundColor' => $payment->status === 'paid' ? '#28a745' : ($payment->status === 'overdue' ? '#dc3545' : '#ffc107'),
                'borderColor' => $payment->status === 'paid' ? '#28a745' : ($payment->status === 'overdue' ? '#dc3545' : '#ffc107'),
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => 'payment',
                    'status' => $payment->status,
                    'amount' => $payment->amount,
                    'category' => $payment->category_text,
                    'url' => route('payments.show', $payment)
                ]
            ];
        });

        return response()->json($events);
    }
}