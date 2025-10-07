<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property float $amount
 * @property string $category
 * @property Carbon $due_date
 * @property Carbon|null $paid_date
 * @property string $status
 * @property string|null $payment_method
 * @property string|null $reference
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $status_color
 * @property string $status_text
 * @property string $category_text
 */
class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'amount',
        'category',
        'due_date',
        'paid_date',
        'status',
        'payment_method',
        'reference',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'float'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'paid' => 'success',
            'pending' => 'warning',
            'overdue' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'paid' => 'Pagado',
            'pending' => 'Pendiente',
            'overdue' => 'Vencido',
            default => 'Desconocido'
        };
    }

    public static function getCategories(): array
    {
        return [
            'colegiatura' => 'Colegiatura',
            'libros' => 'Libros',
            'uniformes' => 'Uniformes',
            'transporte' => 'Transporte',
            'actividades' => 'Actividades',
            'material' => 'Material Escolar',
            'otros' => 'Otros'
        ];
    }

    public function getCategoryTextAttribute(): string
    {
        return self::getCategories()[$this->category] ?? $this->category;
    }
}
