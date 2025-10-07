<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CalendarExportController;
use App\Http\Controllers\HomeController;

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('home.dashboard');
    Route::get('/inicio', [HomeController::class, 'index']);

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('tasks/export/excel', [\App\Http\Controllers\TaskExportController::class, 'excel'])->name('tasks.export.excel');
    Route::get('tasks/export/pdf', [\App\Http\Controllers\TaskExportController::class, 'pdf'])->name('tasks.export.pdf');
    Route::get('events/export/excel', [\App\Http\Controllers\EventExportController::class, 'excel'])->name('events.export.excel');
    Route::get('events/export/pdf', [\App\Http\Controllers\EventExportController::class, 'pdf'])->name('events.export.pdf');
    Route::get('events/export', [\App\Http\Controllers\EventExportController::class, 'excel'])->name('events.export'); // Alias para compatibilidad
    Route::resource('tasks', TaskController::class);
    Route::resource('events', EventController::class);
    Route::get('calendar/events', [EventController::class, 'apiEvents'])->name('calendar.events');
    Route::view('calendar', 'calendar')->name('calendar');
    Route::get('calendar/export', [\App\Http\Controllers\CalendarExportController::class, 'export'])->name('calendar.export');
    Route::get('calendar/export/excel', [\App\Http\Controllers\CalendarExportController::class, 'exportExcel'])->name('calendar.export.excel');
    Route::get('calendar/export/pdf', [\App\Http\Controllers\CalendarExportController::class, 'exportPdf'])->name('calendar.export.pdf');
    
    // Sistema de pagos simple y funcional
    Route::resource('payments', PaymentController::class);
    Route::get('payments-calendar-events', [PaymentController::class, 'getCalendarEvents'])->name('payments.calendar-events');    // Mantener compatibilidad con rutas antiguas (redirigir a las nuevas)
    Route::get('tuition', function() {
        return redirect()->route('payments.index');
    })->name('tuition.index');

});
