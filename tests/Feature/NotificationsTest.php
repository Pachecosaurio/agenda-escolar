<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use App\Notifications\GeneralNotification;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_notifications(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Seed a couple notifications
        $user->notify(new GeneralNotification('Prueba 1', 'Mensaje 1'));
        $user->notify(new GeneralNotification('Prueba 2', 'Mensaje 2'));

        $res = $this->get(route('notifications.index'));
        $res->assertStatus(200);
        $res->assertSee('Notificaciones');
        $res->assertSee('Prueba 1');
        $res->assertSee('Prueba 2');
    }

    public function test_mark_all_read_and_mark_one(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $user->notify(new GeneralNotification('N1', 'M1'));
        $user->notify(new GeneralNotification('N2', 'M2'));

        $this->post(route('notifications.markAllRead'))->assertRedirect();

        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $user->id,
            'read_at' => null,
        ]);

        $user->notify(new GeneralNotification('N3', 'M3'));
        $notif = DatabaseNotification::where('notifiable_id', $user->id)->latest()->first();
        $this->post(route('notifications.markRead', $notif->id))->assertRedirect();
        $this->assertNotNull($notif->fresh()->read_at);
    }

    public function test_destroy_and_destroy_all(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $user->notify(new GeneralNotification('A', 'B'));
        $one = DatabaseNotification::where('notifiable_id', $user->id)->first();

        $this->delete(route('notifications.destroy', $one->id))->assertRedirect();
        $this->assertDatabaseMissing('notifications', ['id' => $one->id]);

        $user->notify(new GeneralNotification('C', 'D'));
        $user->notify(new GeneralNotification('E', 'F'));
        $this->delete(route('notifications.destroyAll'))->assertRedirect();

        $this->assertDatabaseCount('notifications', 0);
    }
}
