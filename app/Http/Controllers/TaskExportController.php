<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\TasksExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Task;
use Barryvdh\DomPDF\PDF;

class TaskExportController extends Controller
{
    public function excel()
    {
        return Excel::download(new TasksExport, 'tareas_' . auth()->user()->name . '_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function pdf()
    {
        $tasks = Task::where('user_id', auth()->id())->orderBy('due_date', 'asc')->get();
        $pdf = app('dompdf.wrapper')->loadView('tasks.export_pdf', compact('tasks'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('tareas_' . auth()->user()->name . '_' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        $tasks = \App\Models\Task::where('user_id', auth()->id())->get();
        $exportData = $tasks->map(function($task) {
            return [
                'Título' => $task->title,
                'Descripción' => $task->description,
                'Fecha límite' => $task->due_date ? date('d/m/Y H:i', strtotime($task->due_date)) : '-',
                'Estado' => $task->completed ? 'Completada' : 'Pendiente',
            ];
        });
        $headings = ['Título', 'Descripción', 'Fecha límite', 'Estado'];
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\StyledArrayExport($exportData->toArray(), $headings),
            'tareas.xlsx'
        );
    }
}
