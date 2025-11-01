<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Payment};

class PaymentsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_payments_and_stats(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Payment::factory()->create(['title' => 'Pago 1', 'status' => 'pending', 'user_id' => $user->id]);
        Payment::factory()->create(['title' => 'Pago 2', 'status' => 'paid', 'user_id' => $user->id]);
        Payment::factory()->create(['title' => 'Pago 3', 'status' => 'overdue', 'user_id' => $user->id]);

        $res = $this->get(route('payments.index'));
        $res->assertStatus(200);
        $res->assertSee('Pago 1');
        $res->assertSee('Pago 2');
        $res->assertSee('Pago 3');

        // Sanity check heading present
        $res->assertSee('Listado de Pagos');
    }

    public function test_payments_calendar_events_endpoint_returns_user_payments_as_events(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $p = Payment::factory()->create(['title' => 'Evento Pago', 'status' => 'pending', 'user_id' => $user->id]);

        $res = $this->getJson(route('payments.calendar-events'));
        $res->assertStatus(200);
        $json = $res->json();
        $titles = collect($json)->pluck('title');
        $this->assertTrue($titles->contains('Evento Pago'));

        // Ensure extendedProps exists with url
        $first = collect($json)->firstWhere('id', 'payment_' . $p->id);
        $this->assertArrayHasKey('extendedProps', $first);
        $this->assertArrayHasKey('url', $first['extendedProps']);
    }
}
