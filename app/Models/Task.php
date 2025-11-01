<?php
/**
 * Modelo Task
 *
 * Tareas simples con título, descripción, fecha de vencimiento opcional,
 * adjunto y estado de completado. Asociadas a un usuario.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'user_id', 'due_date', 'attachment', 'completed'];
}
