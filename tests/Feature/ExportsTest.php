<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User, Task, Event};

class ExportsTest extends TestCase
{
    use RefreshDatabase;

    private function signIn()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_tasks_export_excel_and_pdf(): void
    {
        $user = $this->signIn();
        Task::factory()->create(['user_id' => $user->id, 'title' => 'Tarea X']);

        $excel = $this->get(route('tasks.export.excel'));
        $excel->assertStatus(200);
        $this->assertTrue(str_starts_with(strtolower($excel->headers->get('content-type') ?? ''), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'));

        $pdf = $this->get(route('tasks.export.pdf'));
        $pdf->assertStatus(200);
        $this->assertTrue(str_starts_with(strtolower($pdf->headers->get('content-type') ?? ''), 'application/pdf'));
    }

    public function test_events_export_excel_and_pdf(): void
    {
        $user = $this->signIn();
        Event::factory()->create(['user_id' => $user->id, 'title' => 'Evento X']);

        $excel = $this->get(route('events.export.excel'));
        $excel->assertStatus(200);
        $this->assertTrue(str_starts_with(strtolower($excel->headers->get('content-type') ?? ''), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'));

        $pdf = $this->get(route('events.export.pdf'));
        $pdf->assertStatus(200);
        $this->assertTrue(str_starts_with(strtolower($pdf->headers->get('content-type') ?? ''), 'application/pdf'));
    }

    public function test_calendar_export_excel_and_pdf(): void
    {
        $this->signIn();

        $excel = $this->get(route('calendar.export.excel'));
        $excel->assertStatus(200);
        $this->assertTrue(str_starts_with(strtolower($excel->headers->get('content-type') ?? ''), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'));

        $pdf = $this->get(route('calendar.export.pdf'));
        $pdf->assertStatus(200);
        $this->assertTrue(str_starts_with(strtolower($pdf->headers->get('content-type') ?? ''), 'application/pdf'));
    }
}
