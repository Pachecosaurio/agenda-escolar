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

        return [
            'user_id' => User::factory(),
            'title' => ucfirst(fake()->words(3, true)),
            'description' => fake()->boolean(60) ? fake()->sentence(10) : null,
            'due_date' => $due,
            'attachment' => null,
            'completed' => fake()->boolean(35),
        ];
    }
}
