<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $due = fake()->boolean(80)
            ? Carbon::now()->addDays(fake()->numberBetween(-10, 30))->addHours(fake()->numberBetween(0, 23))
            : null;

        $titulos = [
            'Resolver ejercicios de álgebra', 'Estudiar historia universal', 'Preparar exposición de ciencias',
            'Redactar resumen de lectura', 'Completar práctica de laboratorio', 'Organizar apuntes de clase',
            'Investigar tema de biología', 'Revisar ecuaciones físicas', 'Repasar vocabulario de inglés',
            'Crear mapa conceptual', 'Actualizar cuaderno de tareas', 'Resolver problemas de lógica'
        ];
        $descripciones = [
            'Priorizar los puntos más complejos y anotar dudas.',
            'Usar fuentes confiables y citar correctamente.',
            'Practicar antes de exponer para ganar seguridad.',
            'Sintetizar ideas principales sin perder claridad.',
            'Verificar que todos los pasos estén documentados.',
            'Agrupar la información por categorías para ordenarla.',
            'Consultar material adicional para ejemplos prácticos.',
            'Identificar patrones y revisar errores frecuentes.',
            'Memorizar mediante repetición espaciada y asociaciones.',
            'Conectar conceptos relacionados para mejor comprensión.'
        ];

        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement($titulos),
            'description' => fake()->boolean(65) ? fake()->randomElement($descripciones) : null,
            'due_date' => $due,
            'attachment' => null,
            'completed' => fake()->boolean(35),
        ];
    }
}
