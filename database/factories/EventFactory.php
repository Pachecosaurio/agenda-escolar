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

        return [
            'user_id' => User::factory(),
            'title' => ucfirst(fake()->words(3, true)),
            'description' => fake()->boolean(50) ? fake()->sentence(12) : null,
            'start' => $start,
            'end' => $end,
        ];
    }
}
