<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command?->warn('No users found, skipping PaymentSeeder.');
            return;
        }

        foreach ($users as $user) {
            // Base random payments
            Payment::factory()->count(10)->create([ 'user_id' => $user->id ]);

            // Ensure variety
            Payment::factory()->count(3)->overdue()->create([ 'user_id' => $user->id ]);
            Payment::factory()->count(4)->paid()->create([ 'user_id' => $user->id ]);
        }
    }
}
