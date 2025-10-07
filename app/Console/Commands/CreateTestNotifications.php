<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\GeneralNotification;

class CreateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear notificaciones de prueba para el usuario autenticado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Crear notificaciones de prueba
            $user->notify(new GeneralNotification(
                'Bienvenido',
                'Te damos la bienvenida a tu Agenda Escolar'
            ));
            
            $user->notify(new GeneralNotification(
                'Tarea pendiente',
                'Tienes una tarea de Matemáticas que vence mañana'
            ));
            
            $user->notify(new GeneralNotification(
                'Evento próximo',
                'Recordatorio: Examen de Historia el viernes'
            ));
            
            $user->notify(new GeneralNotification(
                'Sistema',
                'Tu calendario ha sido actualizado correctamente'
            ));
        }
        
        $this->info('Notificaciones de prueba creadas exitosamente para todos los usuarios.');
        
        return 0;
    }
}
