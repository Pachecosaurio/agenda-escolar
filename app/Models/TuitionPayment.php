<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property int $scholarship_percent
 * @property \Carbon\Carbon|null $payment_date
 * @property string $payment_type
 * @property int $penalty_percent
 * @property float $final_amount
 * @property string|null $receipt
 * @property \Carbon\Carbon|null $payment_start
 * @property \Carbon\Carbon|null $payment_end
 * @property string|null $title
 * @property string|null $description
 * @property string $status
 * @property string|null $payment_method
 * @property string|null $reference_number
 * @property int|null $event_id
 * @property bool $show_in_calendar
 * @property string $calendar_color
 * @property \Carbon\Carbon|null $due_date
 * @property \Carbon\Carbon|null $reminder_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read User $user
 * @property-read Event|null $event
 * @property-read string $formatted_status
 * @property-read bool $is_overdue
 */
class TuitionPayment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 
        'amount', 
        'scholarship_percent', 
        'payment_start', 
        'payment_end', 
        'payment_date', 
        'payment_type', 
        'penalty_percent', 
        'final_amount', 
        'receipt',
        'payment_method',
        'reference_number',
        // Campos para eventos del calendario y control personal
        'title',
        'description',
        'status',
        'event_id',
        'show_in_calendar',
        'calendar_color',
        'due_date',
        'reminder_date'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'payment_start' => 'date',
        'payment_end' => 'date',
        'due_date' => 'datetime',
        'reminder_date' => 'datetime',
        'show_in_calendar' => 'boolean',
        'amount' => 'decimal:2',
        'final_amount' => 'decimal:2'
    ];

    // Constantes para estados
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el evento del calendario
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope para pagos pagados
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope para pagos vencidos
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
                    ->orWhere(function($q) {
                        $q->where('status', self::STATUS_PENDING)
                          ->where('due_date', '<', now());
                    });
    }

    /**
     * Scope para pagos que se muestran en calendario
     */
    public function scopeShowInCalendar($query)
    {
        return $query->where('show_in_calendar', true);
    }

    /**
     * Accessor para obtener el estado formateado
     */
    public function getFormattedStatusAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_PAID => 'Pagado',
            self::STATUS_OVERDUE => 'Vencido',
            self::STATUS_CANCELLED => 'Cancelado',
            default => 'Desconocido'
        };
    }

    /**
     * Accessor para verificar si está vencido
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status !== self::STATUS_PENDING || !$this->due_date) {
            return false;
        }
        
        try {
            // Asegurar que tenemos un objeto Carbon válido
            $dueDate = $this->due_date instanceof \Carbon\Carbon 
                ? $this->due_date 
                : \Carbon\Carbon::parse($this->due_date);
            return $dueDate->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Método para marcar como pagado
     */
    public function markAsPaid($paymentDate = null, $paymentMethod = null, $referenceNumber = null)
    {
        $data = [
            'status' => self::STATUS_PAID,
            'payment_date' => $paymentDate ?? now()
        ];
        
        if ($paymentMethod) {
            $data['payment_method'] = $paymentMethod;
        }
        
        if ($referenceNumber) {
            $data['reference_number'] = $referenceNumber;
        }
        
        $this->update($data);

        // Crear o actualizar evento en el calendario si está habilitado
        if ($this->show_in_calendar) {
            $this->createOrUpdateCalendarEvent();
        }
    }

    /**
     * Crear o actualizar evento en el calendario
     */
    public function createOrUpdateCalendarEvent()
    {
        // Obtener fecha de inicio segura
        $startTime = $this->due_date ?? $this->payment_date ?? now();
        if (!$startTime instanceof \Carbon\Carbon) {
            $startTime = \Carbon\Carbon::parse($startTime);
        }
        
        $eventData = [
            'title' => $this->title ?? "Pago de Colegiatura - " . $this->getFormattedStatusAttribute(),
            'description' => $this->description ?? "Pago por \${$this->final_amount}",
            'start_time' => $startTime,
            'end_time' => $startTime->copy()->addHour(),
            'color' => $this->calendar_color ?? '#FF5722',
            'user_id' => $this->user_id
        ];

        if ($this->event_id) {
            // Actualizar evento existente
            $this->event()->update($eventData);
        } else {
            // Crear nuevo evento
            $event = Event::create($eventData);
            $this->update(['event_id' => $event->id]);
        }
    }
}
