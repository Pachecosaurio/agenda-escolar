<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $start = Carbon::now()->addDays(fake()->numberBetween(-7, 30))->addHours(fake()->numberBetween(8, 18));
        $end = (clone $start)->addHours(fake()->numberBetween(1, 3));

        $titulos = [
            'Reunión de proyecto', 'Examen parcial', 'Presentación final', 'Tutoría académica',
            'Sesión de estudio', 'Entrega de informe', 'Práctica de laboratorio', 'Revisión de tareas',
            'Actividad extracurricular', 'Charla motivacional', 'Simulacro de examen'
        ];
        $descripciones = [
            'Repasar los objetivos y pendientes antes de la fecha límite.',
            'Preparar materiales y confirmar asistencia del equipo.',
            'Revisar rúbrica y criterios de evaluación detalladamente.',
            'Llevar apuntes y dudas anotadas para optimizar el tiempo.',
            'Organizar temas según prioridad y dificultad.',
            'Verificar que el informe cumpla el formato solicitado.',
            'Confirmar hipótesis y resultados del trabajo práctico.',
            'Analizar comentarios recibidos para realizar mejoras.',
            'Participar activamente y registrar conclusiones clave.',
            'Tomar notas de ideas aplicables al próximo ciclo.',
            'Practicar gestión de tiempo bajo condiciones similares.'
        ];

        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement($titulos),
            'description' => fake()->boolean(55) ? fake()->randomElement($descripciones) : null,
            'start' => $start,
            'end' => $end,
        ];
    }
}
