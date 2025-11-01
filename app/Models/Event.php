<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;
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
        'recurrence_interval' => 'integer',
        'recurrence_count' => 'integer',
        'parent_event_id' => 'integer',
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
    // Asegurar que el intervalo sea un entero >= 1 para evitar errores de tipo en Carbon
    $interval = max(1, (int)($this->recurrence_interval ?? 1));
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

    /**
     * Genera ocurrencias (sin persistir) dentro de un rango dado.
     * Devuelve un array de arrays con claves 'start' y 'end' (instancias Carbon).
     */
    public function occurrencesBetween(Carbon $rangeStart, Carbon $rangeEnd): array
    {
        $occurrences = [];

        $baseStart = $this->start instanceof Carbon ? $this->start->copy() : Carbon::parse($this->start);
        $baseEnd = $this->end ? ( $this->end instanceof Carbon ? $this->end->copy() : Carbon::parse($this->end) ) : $baseStart->copy()->addHour();

        // Si no es recurrente, incluir solo si cruza el rango
        if (!$this->is_recurring) {
            if ($baseStart <= $rangeEnd && $baseEnd >= $rangeStart) {
                $occurrences[] = ['start' => $baseStart, 'end' => $baseEnd];
            }
            return $occurrences;
        }

        // Recurrente: calcular ocurrencias dentro del rango sin explotar el sistema
        $interval = max(1, (int)($this->recurrence_interval ?? 1));
        $type = $this->recurrence_type ?: 'weekly';
        $limitEnd = $this->recurrence_end_date ? Carbon::parse($this->recurrence_end_date)->endOfDay() : null;
        $hardCap = min((int)($this->recurrence_count ?? 365), 365); // límite de seguridad

        // Punto inicial: la primera ocurrencia que no sea anterior al rango
        $currentStart = $baseStart->copy();
        $currentEnd = $baseEnd->copy();

        // Avanzar hasta caer (aprox) en el rango
        while ($currentEnd < $rangeStart) {
            switch ($type) {
                case 'daily':
                    $currentStart->addDays($interval);
                    $currentEnd->addDays($interval);
                    break;
                case 'weekly':
                    $currentStart->addWeeks($interval);
                    $currentEnd->addWeeks($interval);
                    break;
                case 'monthly':
                    $currentStart->addMonths($interval);
                    $currentEnd->addMonths($interval);
                    break;
                case 'yearly':
                    $currentStart->addYears($interval);
                    $currentEnd->addYears($interval);
                    break;
                default:
                    $currentStart->addWeeks($interval);
                    $currentEnd->addWeeks($interval);
            }
            // Protección por si no avanza (caso raro)
            if ($hardCap-- <= 0) { return $occurrences; }
        }

        // Recolectar ocurrencias dentro del rango
        $count = 0;
        $maxCount = (int)($this->recurrence_count ?? 200);
        $maxCount = min($maxCount, 200); // seguridad
        $windowEnd = $limitEnd ? min($rangeEnd, $limitEnd) : $rangeEnd;

        while ($currentStart <= $windowEnd && $count < $maxCount) {
            // Incluir si cruza el rango
            if ($currentStart <= $rangeEnd && $currentEnd >= $rangeStart) {
                $occurrences[] = ['start' => $currentStart->copy(), 'end' => $currentEnd->copy()];
            }

            // Siguiente
            switch ($type) {
                case 'daily':
                    $currentStart->addDays($interval);
                    $currentEnd->addDays($interval);
                    break;
                case 'weekly':
                    $currentStart->addWeeks($interval);
                    $currentEnd->addWeeks($interval);
                    break;
                case 'monthly':
                    $currentStart->addMonths($interval);
                    $currentEnd->addMonths($interval);
                    break;
                case 'yearly':
                    $currentStart->addYears($interval);
                    $currentEnd->addYears($interval);
                    break;
                default:
                    $currentStart->addWeeks($interval);
                    $currentEnd->addWeeks($interval);
            }
            $count++;
        }

        return $occurrences;
    }
}
