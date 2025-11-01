<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Event, Task};
use Carbon\Carbon;

class CalendarApiTest extends TestCase
{
    use RefreshDatabase;

    private function signIn()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_events_and_tasks_in_range_include_recurring_occurrences(): void
    {
        $user = $this->signIn();
        $start = Carbon::parse('2025-01-01 00:00:00');
        $end = Carbon::parse('2025-01-31 23:59:59');

        // Base one-time event inside range
        Event::factory()->create([
            'title' => 'Único',
            'start' => '2025-01-10 10:00:00',
            'end' => '2025-01-10 11:00:00',
            'is_recurring' => false,
            'user_id' => $user->id,
        ]);

        // Recurring weekly event starting inside the range
        $parent = Event::factory()->create([
            'title' => 'Recurrente',
            'start' => '2025-01-03 09:00:00',
            'end' => '2025-01-03 10:00:00',
            'is_recurring' => true,
            'recurrence_type' => 'weekly',
            'recurrence_interval' => 1,
            'recurrence_count' => 5,
            'user_id' => $user->id,
        ]);

        // Task inside range
        Task::factory()->create([
            'title' => 'Tarea enero',
            'due_date' => '2025-01-15 12:00:00',
            'completed' => false,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('calendar.events', [
            'start' => $start->toIso8601String(),
            'end' => $end->toIso8601String(),
        ]));

        $response->assertStatus(200);
        $data = $response->json();

        // We expect to see the one-time event, multiple occurrences of recurring, and the task mapped
        $titles = collect($data)->pluck('title');
        $this->assertTrue($titles->contains('Único'));
        $this->assertTrue($titles->contains('Recurrente'));
    $this->assertTrue($titles->contains('[Tarea] Tarea enero'));

        // Ensure there are multiple entries for the recurring event title
        $recurringCount = $titles->filter(fn($t) => $t === 'Recurrente')->count();
        $this->assertGreaterThan(1, $recurringCount, 'Recurring occurrences should generate >1 entries');

        // Ensure no materialized children exist in DB for recurring
        $this->assertDatabaseMissing('events', ['parent_event_id' => $parent->id]);
    }
    
    public function test_monthly_recurrence_respects_end_date(): void
    {
        $user = $this->signIn();

        $parent = Event::factory()->create([
            'user_id' => $user->id,
            'title' => 'Mensual',
            'start' => '2025-01-05 10:00:00',
            'end' => '2025-01-05 11:00:00',
            'is_recurring' => true,
            'recurrence_type' => 'monthly',
            'recurrence_interval' => 1,
            'recurrence_end_date' => '2025-03-31',
            'recurrence_count' => 50,
        ]);

        $response = $this->getJson(route('calendar.events', [
            'start' => '2025-01-01T00:00:00Z',
            'end' => '2025-04-30T23:59:59Z',
        ]));
        $response->assertStatus(200);
        $titles = collect($response->json())->pluck('title');
        $this->assertTrue($titles->contains('Mensual'));
        $count = $titles->filter(fn($t) => $t === 'Mensual')->count();
        $this->assertEquals(3, $count, 'Debe haber 3 ocurrencias (enero, febrero, marzo)');

        // Asegurar que no se materialicen hijos
        $this->assertDatabaseMissing('events', ['parent_event_id' => $parent->id]);
    }

    public function test_tasks_out_of_range_and_null_due_date_are_excluded(): void
    {
        $user = $this->signIn();
        // In-range
        Task::factory()->create(['user_id' => $user->id, 'title' => 'En rango', 'due_date' => '2025-01-15 09:00:00']);
        // Out of range
        Task::factory()->create(['user_id' => $user->id, 'title' => 'Fuera de rango', 'due_date' => '2025-02-15 09:00:00']);
        // Null due_date
        Task::factory()->create(['user_id' => $user->id, 'title' => 'Sin fecha', 'due_date' => null]);

        $response = $this->getJson(route('calendar.events', [
            'start' => '2025-01-01T00:00:00Z',
            'end' => '2025-01-31T23:59:59Z',
        ]));
        $response->assertStatus(200);
        $titles = collect($response->json())->pluck('title');
        $this->assertTrue($titles->contains('[Tarea] En rango'));
        $this->assertFalse($titles->contains('[Tarea] Fuera de rango'));
        $this->assertFalse($titles->contains('[Tarea] Sin fecha'));
    }
}
