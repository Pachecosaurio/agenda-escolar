<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class TasksCrudTest extends TestCase
{
    use RefreshDatabase;

    private function signIn()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_can_create_edit_and_delete_task(): void
    {
        $this->signIn();

        // Create
        $create = $this->post(route('tasks.store'), [
            'title' => 'Nueva tarea',
            'description' => 'Descripción',
            'due_date' => now()->addDay()->format('Y-m-d\TH:i'),
        ]);
        $create->assertRedirect();
        $task = Task::latest('id')->first();
        $this->assertNotNull($task);

        // Update
        $update = $this->put(route('tasks.update', $task), [
            'title' => 'Tarea editada',
            'description' => 'Desc 2',
            'due_date' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'completed' => true,
        ]);
        $update->assertRedirect();
        $this->assertEquals('Tarea editada', $task->fresh()->title);
        $this->assertTrue((bool)$task->fresh()->completed);

        // Delete
        $delete = $this->delete(route('tasks.destroy', $task));
        $delete->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_validation_errors_on_create(): void
    {
        $this->signIn();
        $res = $this->post(route('tasks.store'), []);
        $res->assertSessionHasErrors(['title']);
    }
}
