<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TasksExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Task::where('user_id', auth()->id())
                  ->orderBy('due_date', 'asc')
                  ->get(['title', 'description', 'due_date', 'completed', 'created_at'])
                  ->map(function($task) {
                      return [
                          'title' => $task->title,
                          'description' => $task->description ?: 'Sin descripción',
                          'due_date' => $task->due_date ? date('d/m/Y H:i', strtotime($task->due_date)) : 'Sin fecha límite',
                          'completed' => $task->completed ? 'Completada' : 'Pendiente',
                          'created_at' => $task->created_at->format('d/m/Y H:i')
                      ];
                  });
    }

    public function headings(): array
    {
        return [
            'Título', 'Descripción', 'Fecha Límite', 'Estado', 'Creado'
        ];
    }
}
