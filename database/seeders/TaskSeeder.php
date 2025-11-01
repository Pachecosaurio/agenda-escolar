<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command?->warn('No users found, skipping TaskSeeder.');
            return;
        }

        foreach ($users as $user) {
            Task::factory()->count(12)->create([ 'user_id' => $user->id ]);
        }
    }
}
