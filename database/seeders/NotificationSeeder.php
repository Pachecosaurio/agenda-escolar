<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\GeneralNotification;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command?->warn('No users found, skipping NotificationSeeder.');
            return;
        }

        $samples = [
            ['Pago vencido', 'Tienes un pago que venció recientemente. Revisa la sección de Pagos.'],
            ['Recordatorio de evento', 'No olvides tu evento programado para mañana.'],
            ['Tarea próxima a vencer', 'Tienes tareas que vencen esta semana.'],
            ['Pago recibido', '¡Hemos registrado tu pago exitosamente! Gracias.'],
            ['Nuevo evento', 'Se agregó un nuevo evento en tu calendario.']
        ];

        foreach ($users as $user) {
            foreach (range(1, 6) as $i) {
                $pick = fake()->randomElement($samples);
                $title = $pick[0];
                $msg = $pick[1] . ' Ref: #' . Str::upper(Str::random(6));
                // 1-2 read notifications, rest unread
                $user->notify(new GeneralNotification($title, $msg));
            }
        }
    }
}
