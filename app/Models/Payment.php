<?php
/**
 * Modelo Payment
 *
 * Pagos escolares con estados (pending, paid, overdue), categorías y scopes
 * para filtrado/búsqueda. Provee atributos de presentación (status_text,
 * status_color, category_text) y se integra con el calendario vía controlador.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory, SoftDeletes;
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
            'tuition' => 'Colegiatura',
            'books' => 'Libros',
            'activities' => 'Actividades',
            'transport' => 'Transporte',
            'cafeteria' => 'Cafetería',
            'other' => 'Otros'
        ];
    }

    public function getCategoryTextAttribute(): string
    {
        return self::getCategories()[$this->category] ?? ucfirst($this->category);
    }

    // Scopes
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeStatus($query, ?string $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
        return $query;
    }

    public function scopeCategory($query, ?string $category)
    {
        if ($category) {
            $query->where('category', $category);
        }
        return $query;
    }

    public function scopeBetweenDates($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->whereDate('due_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('due_date', '<=', $to);
        }
        return $query;
    }

    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $term = "%{$term}%";
            $query->where(function($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhere('description', 'like', $term)
                  ->orWhere('notes', 'like', $term);
            });
        }
        return $query;
    }
}
