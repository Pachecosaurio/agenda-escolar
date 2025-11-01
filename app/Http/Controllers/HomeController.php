<?php
/**
 * Home/Dashboard Controller
 *
 * / (home): página de bienvenida pública.
 * /home (home.dashboard): dashboard con estadísticas rápidas para usuarios autenticados.
 * Maneja una experiencia simple y robusta: ante errores en estadísticas, cae a la vista home.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Removemos el middleware auth para permitir acceso a invitados
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Simplemente mostrar la vista home sin variables adicionales
        return view('home');
    }

    /**
     * Show the dashboard with statistics for authenticated users
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        try {
            // Obtener tareas próximas a vencer (próximos 7 días)
            $upcomingTasks = Task::where('user_id', $user->id)
                ->where('due_date', '>=', Carbon::today())
                ->where('due_date', '<=', Carbon::today()->addDays(7))
                ->where('status', '!=', 'completada')
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get();

            // Obtener eventos próximos (próximos 7 días)
            $upcomingEvents = Event::where('user_id', $user->id)
                ->where('start_date', '>=', Carbon::today())
                ->where('start_date', '<=', Carbon::today()->addDays(7))
                ->orderBy('start_date', 'asc')
                ->limit(5)
                ->get();

            // Estadísticas rápidas
            $totalTasks = Task::where('user_id', $user->id)->count();
            $completedTasks = Task::where('user_id', $user->id)
                ->where('status', 'completada')
                ->count();
            $pendingTasks = $totalTasks - $completedTasks;
            
            $totalEvents = Event::where('user_id', $user->id)->count();
            
            $todayEvents = Event::where('user_id', $user->id)
                ->whereDate('start_date', Carbon::today())
                ->count();

            return view('dashboard', compact(
                'upcomingTasks',
                'upcomingEvents',
                'totalTasks',
                'completedTasks',
                'pendingTasks',
                'totalEvents',
                'todayEvents'
            ));
        } catch (\Exception $e) {
            // Si hay algún error, redirigir a home
            return view('home');
        }
    }
}
