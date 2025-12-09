<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\Event;
use App\Models\Payment;
use App\Notifications\GeneralNotification;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command?->warn('No users found, skipping NotificationSeeder.');
            return;
        }

        foreach ($users as $user) {
            $notifications = [];

            // 1. Notificación de tareas próximas a vencer (basada en datos reales)
            $upcomingTasks = Task::where('user_id', $user->id)
                ->where('completed', false)
                ->whereNotNull('due_date')
                ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(3)])
                ->get();

            if ($upcomingTasks->isNotEmpty()) {
                $task = $upcomingTasks->first();
                $daysLeft = Carbon::parse($task->due_date)->diffInDays(Carbon::now());
                $notifications[] = [
                    "Tarea próxima a vencer",
                    "La tarea \"{$task->title}\" vence en {$daysLeft} día(s). No olvides completarla a tiempo."
                ];
            }

            // 2. Notificación de pagos vencidos (basada en datos reales)
            $overduePay = Payment::where('user_id', $user->id)
                ->where('status', 'overdue')
                ->orderBy('due_date', 'desc')
                ->first();

            if ($overduePay) {
                $daysOverdue = Carbon::now()->diffInDays(Carbon::parse($overduePay->due_date));
                $notifications[] = [
                    "Pago vencido - Acción requerida",
                    "El pago \"{$overduePay->title}\" de \${$overduePay->amount} venció hace {$daysOverdue} día(s). Regulariza tu situación."
                ];
            }

            // 3. Notificación de pagos pendientes próximos (basada en datos reales)
            $pendingPay = Payment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(5)])
                ->orderBy('due_date', 'asc')
                ->first();

            if ($pendingPay) {
                $daysLeft = Carbon::parse($pendingPay->due_date)->diffInDays(Carbon::now());
                $notifications[] = [
                    "Recordatorio de pago",
                    "El pago \"{$pendingPay->title}\" de \${$pendingPay->amount} vence en {$daysLeft} día(s). Prepara tu pago."
                ];
            }

            // 4. Notificación de evento próximo (basada en datos reales)
            $upcomingEvent = Event::where('user_id', $user->id)
                ->whereBetween('start', [Carbon::now(), Carbon::now()->addDays(2)])
                ->orderBy('start', 'asc')
                ->first();

            if ($upcomingEvent) {
                $eventDate = Carbon::parse($upcomingEvent->start);
                $timeUntil = $eventDate->diffForHumans();
                $notifications[] = [
                    "Evento próximo",
                    "El evento \"{$upcomingEvent->title}\" está programado para {$timeUntil}. ¡No lo olvides!"
                ];
            }

            // 5. Notificación de pago completado (basada en datos reales)
            $paidPayment = Payment::where('user_id', $user->id)
                ->where('status', 'paid')
                ->whereNotNull('paid_date')
                ->orderBy('paid_date', 'desc')
                ->first();

            if ($paidPayment) {
                $paidDate = Carbon::parse($paidPayment->paid_date)->format('d/m/Y');
                $notifications[] = [
                    "Pago registrado",
                    "Tu pago \"{$paidPayment->title}\" de \${$paidPayment->amount} fue registrado el {$paidDate}. ¡Gracias por tu puntualidad!"
                ];
            }

            // Enviar hasta 5 notificaciones reales (o las que haya disponibles)
            $notificationsToSend = array_slice($notifications, 0, 5);
            
            foreach ($notificationsToSend as $notification) {
                $user->notify(new GeneralNotification($notification[0], $notification[1]));
            }

            // Si no hay suficientes notificaciones reales, completar con genéricas
            $remaining = 5 - count($notificationsToSend);
            if ($remaining > 0) {
                $genericNotifications = [
                    ["Bienvenida al sistema", "Explora todas las funcionalidades: tareas, calendario, pagos y notificaciones."],
                    ["Consejo del día", "Organiza tus tareas por prioridad para ser más productivo."],
                    ["Actualización disponible", "Revisa las nuevas funcionalidades agregadas al sistema."]
                ];

                for ($i = 0; $i < $remaining; $i++) {
                    $pick = fake()->randomElement($genericNotifications);
                    $user->notify(new GeneralNotification($pick[0], $pick[1]));
                }
            }
        }
    }
}
