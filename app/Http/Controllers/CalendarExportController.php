<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Task;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StyledArrayExport;
use Barryvdh\DomPDF\PDF;

class CalendarExportController extends Controller
{
    public function export()
    {
        $events = Event::where('user_id', auth()->id())->get();
        $tasks = Task::where('user_id', auth()->id())->whereNotNull('due_date')->get();
        $ics = [];
        $ics[] = 'BEGIN:VCALENDAR';
        $ics[] = 'VERSION:2.0';
        $ics[] = 'PRODID:-//Agenda Escolar//ES';

        foreach ($events as $event) {
            $ics[] = 'BEGIN:VEVENT';
            $ics[] = 'SUMMARY:' . addslashes($event->title);
            $ics[] = 'DESCRIPTION:' . addslashes($event->description ?? '');
            $ics[] = 'DTSTART:' . date('Ymd\THis', strtotime($event->start));
            $ics[] = 'DTEND:' . date('Ymd\THis', strtotime($event->end));
            $ics[] = 'END:VEVENT';
        }
        foreach ($tasks as $task) {
            $ics[] = 'BEGIN:VEVENT';
            $ics[] = 'SUMMARY:[Tarea] ' . addslashes($task->title);
            $ics[] = 'DTSTART:' . date('Ymd\THis', strtotime($task->due_date));
            $ics[] = 'DTEND:' . date('Ymd\THis', strtotime($task->due_date));
            $ics[] = 'END:VEVENT';
        }
        $ics[] = 'END:VCALENDAR';
        $icsContent = implode("\r\n", $ics);
        return response($icsContent)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename=agenda_' . auth()->user()->name . '_' . now()->format('Y-m-d') . '.ics');
    }

    public function exportExcel(Request $request)
    {
        $userId = auth()->id();
        $events = Event::where('user_id', $userId)->orderBy('start', 'asc')->get();
        $tasks = Task::where('user_id', $userId)->orderBy('due_date', 'asc')->get();
        $items = [];
        foreach ($events as $event) {
            $items[] = [
                'Tipo' => 'Evento',
                'Título' => $event->title,
                'Descripción' => $event->description ?: 'Sin descripción',
                'Fecha inicio' => $event->start ? date('d/m/Y H:i', strtotime($event->start)) : '-',
                'Fecha fin' => $event->end ? date('d/m/Y H:i', strtotime($event->end)) : '-',
            ];
        }
        foreach ($tasks as $task) {
            $items[] = [
                'Tipo' => 'Tarea',
                'Título' => $task->title,
                'Descripción' => $task->description ?: 'Sin descripción',
                'Fecha inicio' => $task->due_date ? date('d/m/Y H:i', strtotime($task->due_date)) : '-',
                'Fecha fin' => $task->due_date ? date('d/m/Y H:i', strtotime($task->due_date)) : '-',
            ];
        }
        $headings = ['Tipo', 'Título', 'Descripción', 'Fecha inicio', 'Fecha fin'];
        return Excel::download(new StyledArrayExport($items, $headings), 'calendario_' . auth()->user()->name . '_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $userId = auth()->id();
        $events = \App\Models\Event::where('user_id', $userId)->orderBy('start', 'asc')->get();
        $tasks = \App\Models\Task::where('user_id', $userId)->orderBy('due_date', 'asc')->get();
        
        $items = [];
        
        // Agregar eventos
        foreach ($events as $event) {
            $items[] = [
                'type' => 'evento',
                'title' => $event->title,
                'description' => $event->description ?: 'Sin descripción',
                'start' => $event->start ? date('d/m/Y H:i', strtotime($event->start)) : '-',
                'end' => $event->end ? date('d/m/Y H:i', strtotime($event->end)) : '-',
                'created_at' => $event->created_at->format('d/m/Y'),
                'sort_date' => $event->start ? strtotime($event->start) : 0,
            ];
        }
        
        // Agregar tareas
        foreach ($tasks as $task) {
            $items[] = [
                'type' => 'tarea',
                'title' => $task->title,
                'description' => $task->description ?: 'Sin descripción',
                'start' => $task->due_date ? date('d/m/Y H:i', strtotime($task->due_date)) : 'Sin fecha límite',
                'end' => $task->due_date ? date('d/m/Y H:i', strtotime($task->due_date)) : 'Sin fecha límite',
                'created_at' => $task->created_at->format('d/m/Y'),
                'sort_date' => $task->due_date ? strtotime($task->due_date) : PHP_INT_MAX,
            ];
        }
        
        // Ordenar por fecha
        usort($items, function($a, $b) {
            return $a['sort_date'] <=> $b['sort_date'];
        });
        
        $pdf = app('dompdf.wrapper')->loadView('calendar.pdf', compact('items'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('calendario_' . auth()->user()->name . '_' . now()->format('Y-m-d') . '.pdf');
    }
}
