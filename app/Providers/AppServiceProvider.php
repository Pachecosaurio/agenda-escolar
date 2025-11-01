<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Usar estilos de Bootstrap para la paginación en todas las vistas
        Paginator::useBootstrap();

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $now = Carbon::now();
                $soon = $now->copy()->addDays(3);
                $tasksSoon = Task::where('user_id', Auth::id())
                    ->whereNotNull('due_date')
                    ->whereBetween('due_date', [$now, $soon])
                    ->get();
                $eventsSoon = Event::whereBetween('start', [$now, $soon])->get();
                $view->with(compact('tasksSoon', 'eventsSoon'));
            }
        });
    }
}
