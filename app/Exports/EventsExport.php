<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Event::where('user_id', auth()->id())
                   ->orderBy('start', 'asc')
                   ->get(['title', 'description', 'start', 'end', 'created_at'])
                   ->map(function($event) {
                       return [
                           'title' => $event->title,
                           'description' => $event->description ?: 'Sin descripción',
                           'start' => $event->start ? date('d/m/Y H:i', strtotime($event->start)) : '',
                           'end' => $event->end ? date('d/m/Y H:i', strtotime($event->end)) : '',
                           'created_at' => $event->created_at->format('d/m/Y H:i')
                       ];
                   });
    }

    public function headings(): array
    {
        return [
            'Título', 'Descripción', 'Fecha Inicio', 'Fecha Fin', 'Creado'
        ];
    }
}
