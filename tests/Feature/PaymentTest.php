<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_create_a_payment(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('payments.store'), [
            'title' => 'Mensualidad Septiembre',
            'amount' => 150.75,
            'category' => 'tuition',
            'due_date' => Carbon::now()->addDays(5)->toDateString(),
            'status' => 'pending'
        ]);

        $response->assertRedirect(route('payments.index'));
        $this->assertDatabaseHas('payments', [
            'title' => 'Mensualidad Septiembre',
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function cannot_create_with_invalid_category(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('payments.store'), [
            'title' => 'Pago X',
            'amount' => 50,
            'category' => 'invalid-cat',
            'due_date' => Carbon::now()->addDay()->toDateString(),
            'status' => 'pending'
        ]);

        $response->assertSessionHasErrors('category');
        $this->assertDatabaseCount('payments', 0);
    }

    #[Test]
    public function filter_by_status_in_index(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        Payment::factory()->count(3)->state(['user_id' => $user->id, 'status' => 'pending'])->create();
        Payment::factory()->count(2)->state(['user_id' => $user->id, 'status' => 'paid'])->create();

        $response = $this->get(route('payments.index', ['status' => 'paid']));
        $response->assertStatus(200);
        $this->assertEquals(2, $response->viewData('payments')->total());
    }

    #[Test]
    public function pending_past_due_payments_become_overdue_on_index(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $pastDue = Payment::factory()->state([
            'user_id' => $user->id,
            'due_date' => Carbon::now()->subDays(3)->toDateString(),
            'status' => 'pending'
        ])->create();

        $this->get(route('payments.index'));

        $this->assertDatabaseHas('payments', [
            'id' => $pastDue->id,
            'status' => 'overdue'
        ]);
    }
}
