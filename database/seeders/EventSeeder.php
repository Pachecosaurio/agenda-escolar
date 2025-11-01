<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command?->warn('No users found, skipping EventSeeder.');
            return;
        }

        foreach ($users as $user) {
            Event::factory()->count(10)->create([ 'user_id' => $user->id ]);
        }
    }
}
