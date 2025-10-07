<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'user_id',
        'is_recurring',
        'recurrence_type',
        'recurrence_interval',
        'recurrence_days',
        'recurrence_end_date',
        'recurrence_count',
        'parent_event_id',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'recurrence_end_date' => 'date',
        'recurrence_days' => 'array',
        'is_recurring' => 'boolean',
    ];

    // Relación con evento padre
    public function parentEvent()
    {
        return $this->belongsTo(Event::class, 'parent_event_id');
    }

    // Relación con eventos hijos
    public function childEvents()
    {
        return $this->hasMany(Event::class, 'parent_event_id');
    }

    // Verificar si es un evento recurrente padre
    public function isRecurringParent()
    {
        return $this->is_recurring && $this->parent_event_id === null;
    }

    // Verificar si es un evento generado
    public function isGeneratedEvent()
    {
        return $this->parent_event_id !== null;
    }

    // Generar eventos recurrentes
    public function generateRecurringEvents()
    {
        if (!$this->is_recurring || $this->parent_event_id !== null) {
            return;
        }

        // Eliminar eventos generados previamente
        $this->childEvents()->delete();

        $startDate = $this->start;
        $endDate = $this->recurrence_end_date ? Carbon::parse($this->recurrence_end_date) : $startDate->copy()->addYear();
        $interval = $this->recurrence_interval;
        $count = 0;
        $maxCount = $this->recurrence_count ?? 100; // límite de seguridad

        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate && $count < $maxCount) {
            $count++;
            
            // Calcular siguiente fecha basada en el tipo de recurrencia
            switch ($this->recurrence_type) {
                case 'daily':
                    $currentDate->addDays($interval);
                    break;
                case 'weekly':
                    $currentDate->addWeeks($interval);
                    break;
                case 'monthly':
                    $currentDate->addMonths($interval);
                    break;
                case 'yearly':
                    $currentDate->addYears($interval);
                    break;
            }

            if ($currentDate <= $endDate) {
                $duration = $this->start->diffInMinutes($this->end);
                $newEnd = $currentDate->copy()->addMinutes($duration);

                Event::create([
                    'title' => $this->title,
                    'description' => $this->description,
                    'start' => $currentDate->copy(),
                    'end' => $newEnd,
                    'user_id' => $this->user_id,
                    'is_recurring' => false,
                    'parent_event_id' => $this->id,
                ]);
            }
        }
    }
}
