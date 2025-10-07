<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PaymentRecord;
use Carbon\Carbon;

class PaymentRecordSeeder extends Seeder
{
    public function run()
    {
        // Obtener el primer usuario
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Usuario Ejemplo',
                'email' => 'usuario@ejemplo.com',
                'password' => bcrypt('password')
            ]);
        }

        // Crear registros de ejemplo
        $records = [
            [
                'title' => 'Colegiatura Septiembre 2025',
                'description' => 'Pago mensual de colegiatura correspondiente al mes de septiembre',
                'amount' => 2500.00,
                'payment_type' => 'mensual',
                'category' => 'colegiatura',
                'due_date' => Carbon::parse('2025-09-05'),
                'payment_date' => Carbon::parse('2025-09-03'),
                'status' => 'paid',
                'payment_method' => 'transferencia',
                'reference_number' => 'TRF-20250903-001',
                'notes' => 'Pago realizado mediante transferencia bancaria',
                'show_in_calendar' => true,
                'calendar_color' => '#28a745'
            ],
            [
                'title' => 'Libros de Texto - Semestre Otoño',
                'description' => 'Compra de libros y materiales para el semestre de otoño 2025',
                'amount' => 850.00,
                'payment_type' => 'semestral',
                'category' => 'libros',
                'due_date' => Carbon::parse('2025-08-15'),
                'payment_date' => Carbon::parse('2025-08-12'),
                'status' => 'paid',
                'payment_method' => 'tarjeta_credito',
                'reference_number' => 'CC-20250812-002',
                'notes' => 'Compra en librería universitaria',
                'show_in_calendar' => true,
                'calendar_color' => '#17a2b8'
            ],
            [
                'title' => 'Colegiatura Octubre 2025',
                'description' => 'Pago mensual de colegiatura correspondiente al mes de octubre',
                'amount' => 2500.00,
                'payment_type' => 'mensual',
                'category' => 'colegiatura',
                'due_date' => Carbon::parse('2025-10-05'),
                'status' => 'pending',
                'notes' => 'Pendiente de pago - recordar hacer transferencia',
                'show_in_calendar' => true,
                'calendar_color' => '#ffc107',
                'reminder_days_before' => 3
            ],
            [
                'title' => 'Uniformes Deportivos',
                'description' => 'Compra de uniformes para actividades deportivas',
                'amount' => 320.00,
                'payment_type' => 'unico',
                'category' => 'uniformes',
                'due_date' => Carbon::parse('2025-09-20'),
                'status' => 'pending',
                'payment_method' => 'efectivo',
                'notes' => 'Compra programada en tienda de uniformes',
                'show_in_calendar' => true,
                'calendar_color' => '#fd7e14'
            ],
            [
                'title' => 'Transporte Escolar - Septiembre',
                'description' => 'Pago del servicio de transporte escolar del mes de septiembre',
                'amount' => 480.00,
                'payment_type' => 'mensual',
                'category' => 'transporte',
                'due_date' => Carbon::parse('2025-09-01'),
                'payment_date' => Carbon::parse('2025-08-30'),
                'status' => 'paid',
                'payment_method' => 'efectivo',
                'notes' => 'Pago realizado directamente al chofer',
                'show_in_calendar' => false
            ],
            [
                'title' => 'Actividad Extracurricular - Música',
                'description' => 'Inscripción a clases de música como actividad extracurricular',
                'amount' => 600.00,
                'payment_type' => 'trimestral',
                'category' => 'actividades',
                'due_date' => Carbon::parse('2025-09-15'),
                'status' => 'pending',
                'notes' => 'Incluye materiales y clases por 3 meses',
                'show_in_calendar' => true,
                'calendar_color' => '#6f42c1',
                'reminder_days_before' => 5
            ],
            [
                'title' => 'Seguro Escolar Anual',
                'description' => 'Pago del seguro escolar que cubre todo el año académico 2025-2026',
                'amount' => 180.00,
                'payment_type' => 'anual',
                'category' => 'otros',
                'due_date' => Carbon::parse('2025-08-01'),
                'payment_date' => Carbon::parse('2025-07-28'),
                'status' => 'paid',
                'payment_method' => 'cheque',
                'reference_number' => 'CHQ-001456',
                'notes' => 'Seguro válido hasta julio 2026',
                'show_in_calendar' => false
            ]
        ];

        foreach ($records as $record) {
            $record['user_id'] = $user->id;
            $record['period_start'] = $record['due_date']->copy()->startOfMonth();
            $record['period_end'] = $record['due_date']->copy()->endOfMonth();
            
            PaymentRecord::create($record);
        }

        $this->command->info("Se crearon " . count($records) . " registros de pagos de ejemplo para el usuario: {$user->email}");
    }
}