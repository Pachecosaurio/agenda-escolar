<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::where('user_id', auth()->id());
        
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
            'user_id' => auth()->id(),
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

        // Generar eventos recurrentes si es necesario
        if ($event->is_recurring) {
            $event->generateRecurringEvents();
        }

        return redirect()->route('events.index')->with('success', 
            $event->is_recurring ? 
            'Evento recurrente creado correctamente con sus repeticiones' : 
            'Evento creado correctamente'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

        // Regenerar eventos recurrentes si es un evento padre y está habilitada la recurrencia
        if ($event->isRecurringParent() && $event->is_recurring) {
            $event->generateRecurringEvents();
        } elseif ($event->isRecurringParent() && !$event->is_recurring) {
            // Si se desactiva la recurrencia, eliminar eventos generados
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
    public function apiEvents()
    {
        $userId = auth()->id();
        $events = \App\Models\Event::where('user_id', $userId)->get()->map(function($event) {
            return [
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'description' => $event->description,
                'extendedProps' => [
                    'type' => 'event',
                    'description' => $event->description,
                ],
                'backgroundColor' => '#667eea',
                'borderColor' => '#764ba2',
            ];
        });
        $tasks = \App\Models\Task::where('user_id', $userId)->whereNotNull('due_date')->get()->map(function($task) {
            return [
                'title' => '[Tarea] ' . $task->title,
                'start' => $task->due_date,
                'end' => $task->due_date,
                'extendedProps' => [
                    'type' => 'task',
                    'description' => $task->description ?? '',
                ],
                'backgroundColor' => '#ffd700',
                'borderColor' => '#ffed4e',
            ];
        });
        return response()->json($events->concat($tasks));
    }
}
