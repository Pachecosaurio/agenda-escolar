<?php

namespace App\Http\Controllers;

use App\Exports\EventsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\PDF;
use App\Models\Event;

class EventExportController extends Controller
{
    public function excel()
    {
        return Excel::download(new EventsExport, 'eventos_' . auth()->user()->name . '_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function pdf()
    {
        $events = Event::where('user_id', auth()->id())->orderBy('start', 'asc')->get();
        $pdf = app('dompdf.wrapper')->loadView('events.export_pdf', compact('events'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('eventos_' . auth()->user()->name . '_' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        $events = \App\Models\Event::where('user_id', auth()->id())->get();
        $exportData = $events->map(function($event) {
            return [
                'Título' => $event->title,
                'Descripción' => $event->description,
                'Inicio' => $event->start ? date('d/m/Y H:i', strtotime($event->start)) : '-',
                'Fin' => $event->end ? date('d/m/Y H:i', strtotime($event->end)) : '-',
            ];
        });
        $headings = ['Título', 'Descripción', 'Inicio', 'Fin'];
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\StyledArrayExport($exportData->toArray(), $headings),
            'eventos.xlsx'
        );
    }
}
