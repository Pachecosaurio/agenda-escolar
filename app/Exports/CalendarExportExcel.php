<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CalendarExportExcel implements FromCollection, WithHeadings
{
    public function collection()
    {
        $userId = auth()->id();
        $events = Event::where('user_id', $userId)->orderBy('start', 'asc')->get(['id', 'title', 'description', 'start', 'end', 'created_at', 'updated_at']);
        $tasks = Task::where('user_id', $userId)->orderBy('due_date', 'asc')->get(['id', 'title', 'description', 'due_date', 'created_at', 'updated_at']);
        
        // Unir eventos y tareas en una sola colección
        return $events->map(function($e) {
            return [
                'Tipo' => 'Evento',
                'Título' => $e->title,
                'Descripción' => $e->description ?: 'Sin descripción',
                'Inicio' => $e->start ? date('d/m/Y H:i', strtotime($e->start)) : '',
                'Fin' => $e->end ? date('d/m/Y H:i', strtotime($e->end)) : '',
                'Fecha límite' => '',
                'Creado' => $e->created_at->format('d/m/Y H:i'),
                'Actualizado' => $e->updated_at->format('d/m/Y H:i')
            ];
        })->concat($tasks->map(function($t) {
            return [
                'Tipo' => 'Tarea',
                'Título' => $t->title,
                'Descripción' => $t->description ?: 'Sin descripción',
                'Inicio' => '',
                'Fin' => '',
                'Fecha límite' => $t->due_date ? date('d/m/Y H:i', strtotime($t->due_date)) : 'Sin fecha límite',
                'Creado' => $t->created_at->format('d/m/Y H:i'),
                'Actualizado' => $t->updated_at->format('d/m/Y H:i')
            ];
        }));
    }

    public function headings(): array
    {
        return [
            'Tipo', 'Título', 'Descripción', 'Inicio', 'Fin', 'Fecha límite', 'Creado', 'Actualizado'
        ];
    }
}
