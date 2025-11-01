<?php
/**
 * Controlador de Eventos
 *
 * Responsabilidades:
 * - CRUD de eventos del usuario autenticado.
 * - Listados excluyendo ocurrencias generadas (parent_event_id no nulo).
 * - API para FullCalendar (apiEvents) que expande recurrencias on-the-fly
 *   dentro del rango solicitado sin materializar registros hijos en BD.
 * - Incluye tareas en el rango para mostrarlas en el calendario.
 */

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::where('user_id', Auth::id())
            ->whereNull('parent_event_id'); // No listar ocurrencias generadas
        
        // Aplicar filtros
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('start')) {
            $query->whereDate('start', $request->start);
        }
        
        $events = $query->orderBy('start', 'desc')->get();
        
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'is_recurring' => 'boolean',
            'recurrence_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_interval' => 'nullable|integer|min:1|max:52',
            'recurrence_end_date' => 'nullable|date|after:start',
            'recurrence_count' => 'nullable|integer|min:1|max:365',
        ]);

        $eventData = [
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
            'user_id' => Auth::id(),
            'is_recurring' => $request->boolean('is_recurring'),
        ];

        // Agregar datos de recurrencia si está habilitada
        if ($request->boolean('is_recurring')) {
            $eventData['recurrence_type'] = $request->recurrence_type;
            $eventData['recurrence_interval'] = $request->recurrence_interval ?? 1;
            $eventData['recurrence_end_date'] = $request->recurrence_end_date;
            $eventData['recurrence_count'] = $request->recurrence_count;
        }

        $event = Event::create($eventData);

        return redirect()->route('events.index')->with('success', 
            $event->is_recurring ? 
            'Evento recurrente creado correctamente con sus repeticiones' : 
            'Evento creado correctamente'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'is_recurring' => 'boolean',
            'recurrence_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_interval' => 'nullable|integer|min:1|max:52',
            'recurrence_end_date' => 'nullable|date|after:start',
            'recurrence_count' => 'nullable|integer|min:1|max:365',
        ]);

        $eventData = [
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'end' => $request->end,
        ];

        // Solo manejar recurrencia si no es un evento generado
        if (!$event->isGeneratedEvent()) {
            $eventData['is_recurring'] = $request->boolean('is_recurring');
            
            if ($request->boolean('is_recurring')) {
                $eventData['recurrence_type'] = $request->recurrence_type;
                $eventData['recurrence_interval'] = $request->recurrence_interval ?? 1;
                $eventData['recurrence_end_date'] = $request->recurrence_end_date;
                $eventData['recurrence_count'] = $request->recurrence_count;
            } else {
                // Si se desactiva la recurrencia, limpiar campos relacionados
                $eventData['recurrence_type'] = null;
                $eventData['recurrence_interval'] = 1;
                $eventData['recurrence_end_date'] = null;
                $eventData['recurrence_count'] = null;
            }
        }

        $event->update($eventData);

        // Ya no materializamos ocurrencias; se calculan on-the-fly en el calendario.
        // Si existieran eventos generados previamente y se desactiva la recurrencia, limpiarlos:
        if ($event->isRecurringParent() && !$event->is_recurring) {
            $event->childEvents()->delete();
        }

        return redirect()->route('events.index')->with('success', 
            $event->is_recurring ? 
            'Evento recurrente actualizado correctamente' : 
            'Evento actualizado correctamente'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Evento eliminado correctamente');
    }

    /**
     * Display a listing of the resource in JSON format for FullCalendar.
     */
    public function apiEvents(Request $request)
    {
        $userId = Auth::id();
        // FullCalendar envía 'start' y 'end' en ISO8601
        $rangeStart = $request->query('start') ? Carbon::parse($request->query('start')) : Carbon::now()->startOfMonth();
        $rangeEnd = $request->query('end') ? Carbon::parse($request->query('end')) : Carbon::now()->endOfMonth();

        $results = [];

        // Eventos (expandir recurrencia on-the-fly)
        $events = Event::where('user_id', $userId)
            ->whereNull('parent_event_id') // Evitar duplicados: no incluir hijos generados
            ->get();
        foreach ($events as $event) {
            if ($event->is_recurring && $event->parent_event_id === null) {
                $occurs = $event->occurrencesBetween($rangeStart, $rangeEnd);
                foreach ($occurs as $idx => $occ) {
                    $results[] = [
                        'id' => 'event_' . $event->id . '_occ_' . $idx,
                        'title' => $event->title,
                        'start' => $occ['start']->toIso8601String(),
                        'end' => $occ['end']->toIso8601String(),
                        'description' => $event->description,
                        'extendedProps' => [
                            'type' => 'event',
                            'description' => $event->description,
                            'url' => route('events.show', $event),
                        ],
                        'backgroundColor' => '#667eea',
                        'borderColor' => '#764ba2',
                    ];
                }
            } else {
                // Evento simple (o generado existente)
                $start = $event->start instanceof Carbon ? $event->start : Carbon::parse($event->start);
                $end = $event->end ? ($event->end instanceof Carbon ? $event->end : Carbon::parse($event->end)) : $start;
                if ($start <= $rangeEnd && $end >= $rangeStart) {
                    $results[] = [
                        'id' => 'event_' . $event->id,
                        'title' => $event->title,
                        'start' => $start->toIso8601String(),
                        'end' => $end->toIso8601String(),
                        'description' => $event->description,
                        'extendedProps' => [
                            'type' => 'event',
                            'description' => $event->description,
                            'url' => route('events.show', $event),
                        ],
                        'backgroundColor' => '#667eea',
                        'borderColor' => '#764ba2',
                    ];
                }
            }
        }

        // Tareas (filtrar por rango para no sobrecargar)
        $tasks = \App\Models\Task::where('user_id', $userId)
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$rangeStart->toDateTimeString(), $rangeEnd->toDateTimeString()])
            ->get();
        foreach ($tasks as $task) {
            $results[] = [
                'id' => 'task_' . $task->id,
                'title' => '[Tarea] ' . $task->title,
                'start' => Carbon::parse($task->due_date)->toIso8601String(),
                'end' => Carbon::parse($task->due_date)->toIso8601String(),
                'description' => $task->description,
                'extendedProps' => [
                    'type' => 'task',
                    'description' => $task->description ?? '',
                    'url' => route('tasks.show', $task),
                ],
                'backgroundColor' => '#ffd700',
                'borderColor' => '#ffed4e',
            ];
        }

        return response()->json($results);
    }
}
