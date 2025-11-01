<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $categories = array_keys(Payment::getCategories());
        $dueDate = Carbon::now()->addDays(fake()->numberBetween(-15, 30))->startOfDay();
        $status = 'pending';
        $paidDate = null;

        // Decide if paid
        if (fake()->boolean(35)) { // 35% chance paid
            $paidDate = (clone $dueDate)->addDays(fake()->numberBetween(0, 3));
            $status = 'paid';
        } elseif ($dueDate->isPast()) {
            $status = 'overdue';
        }

        return [
            'user_id' => User::factory(),
            'title' => ucfirst(fake()->words(3, true)),
            'description' => fake()->boolean(40) ? fake()->sentence() : null,
            'amount' => fake()->randomFloat(2, 10, 500),
            'category' => fake()->randomElement($categories),
            'due_date' => $dueDate->toDateString(),
            'paid_date' => $paidDate?->toDateString(),
            'status' => $status,
            'payment_method' => $status === 'paid' ? fake()->randomElement(['cash','card','transfer','online']) : null,
            'reference' => $status === 'paid' ? strtoupper(Str::random(10)) : null,
            'notes' => fake()->boolean(30) ? fake()->sentence(8) : null,
        ];
    }

    public function paid(): self
    {
        return $this->state(function(array $attributes) {
            $due = Carbon::parse($attributes['due_date']);
            $paidDate = (clone $due)->addDay();
            return [
                'status' => 'paid',
                'paid_date' => $paidDate->toDateString(),
                'payment_method' => 'transfer',
                'reference' => strtoupper(Str::random(10))
            ];
        });
    }

    public function overdue(): self
    {
        return $this->state(function(array $attributes) {
            $due = Carbon::now()->subDays(fake()->numberBetween(1,10));
            return [
                'due_date' => $due->toDateString(),
                'status' => 'overdue',
                'paid_date' => null,
                'payment_method' => null,
                'reference' => null
            ];
        });
    }
}
