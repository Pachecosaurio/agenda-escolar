<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $user = Auth::user();
        $unreadOnly = $request->boolean('unread');
        $base = DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user));
        $query = (clone $base)->orderByDesc('created_at');
        if ($unreadOnly) {
            $query->whereNull('read_at');
        }
        $notifications = $query->paginate(12)->withQueryString();

        $counts = [
            'total' => (clone $base)->count(),
            'unread' => (clone $base)->whereNull('read_at')->count(),
        ];

        return view('notifications.index', compact('notifications', 'counts', 'unreadOnly'));
    }

    public function markAllRead(): RedirectResponse
    {
        $user = Auth::user();
        DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function markRead(string $id): RedirectResponse
    {
        $user = Auth::user();
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->firstOrFail();
        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }
        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function destroyAll(): RedirectResponse
    {
        $user = Auth::user();
        DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->delete();
        return back()->with('success', 'Todas las notificaciones eliminadas.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $user = Auth::user();
        DatabaseNotification::where('id', $id)
            ->where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->delete();
        return back()->with('success', 'Notificación eliminada.');
    }
}
