<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Event, Task, Payment};
use App\Notifications\GeneralNotification;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;

class AuthIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_calendar_api_isolates_user_data(): void
    {
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
        $this->actingAs($u1);

        // Event for user1 and user2
        Event::factory()->create([
            'user_id' => $u1->id,
            'title' => 'Evento U1',
            'start' => '2025-01-10 10:00:00',
            'end' => '2025-01-10 11:00:00',
        ]);
        Event::factory()->create([
            'user_id' => $u2->id,
            'title' => 'Evento U2',
            'start' => '2025-01-11 10:00:00',
            'end' => '2025-01-11 11:00:00',
        ]);

        // Task for user2 (should not appear) and one for user1
        Task::factory()->create(['user_id' => $u2->id, 'title' => 'Tarea U2', 'due_date' => '2025-01-12 12:00:00']);
        Task::factory()->create(['user_id' => $u1->id, 'title' => 'Tarea U1', 'due_date' => '2025-01-12 12:00:00']);

        $res = $this->getJson(route('calendar.events', [
            'start' => '2025-01-01T00:00:00Z',
            'end' => '2025-01-31T23:59:59Z',
        ]));
        $res->assertStatus(200);
        $titles = collect($res->json())->pluck('title');

        $this->assertTrue($titles->contains('Evento U1'));
        $this->assertTrue($titles->contains('[Tarea] Tarea U1'));
        $this->assertFalse($titles->contains('Evento U2'));
        $this->assertFalse($titles->contains('[Tarea] Tarea U2'));
    }

    public function test_payments_calendar_events_isolates_user_data(): void
    {
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
        $this->actingAs($u1);

        Payment::factory()->create(['user_id' => $u1->id, 'title' => 'Pago U1']);
        Payment::factory()->create(['user_id' => $u2->id, 'title' => 'Pago U2']);

        $res = $this->getJson(route('payments.calendar-events'));
        $res->assertStatus(200);
        $titles = collect($res->json())->pluck('title');
        $this->assertTrue($titles->contains('Pago U1'));
        $this->assertFalse($titles->contains('Pago U2'));
    }

    public function test_notifications_index_isolates_user_data(): void
    {
        $u1 = User::factory()->create();
        $u2 = User::factory()->create();
        $this->actingAs($u1);

        $u1->notify(new GeneralNotification('N1-U1', 'M1'));
        $u2->notify(new GeneralNotification('N1-U2', 'M2'));

        $res = $this->get(route('notifications.index'));
        $res->assertStatus(200);
        $res->assertSee('N1-U1');
        $res->assertDontSee('N1-U2');
    }
}
