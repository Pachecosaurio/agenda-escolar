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
            // 5 pagos total: 3 pending, 1 overdue, 1 paid
            Payment::factory()->count(3)->create([ 'user_id' => $user->id ]);
            Payment::factory()->count(1)->overdue()->create([ 'user_id' => $user->id ]);
            Payment::factory()->count(1)->paid()->create([ 'user_id' => $user->id ]);
        }
    }
}
