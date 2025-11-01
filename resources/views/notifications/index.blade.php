@extends('layouts.app')

@push('styles')
<style>
    .hero-notifications { background:linear-gradient(135deg,#667eea,#764ba2); position:relative; overflow:hidden; }
    .hero-notifications .float { position:absolute; border-radius:50%; background:rgba(255,255,255,.12); filter:blur(2px); animation: drift 10s ease-in-out infinite; }
    .hero-notifications .float:nth-child(1){ width:160px; height:160px; top:10%; left:8%; }
    .hero-notifications .float:nth-child(2){ width:220px; height:220px; bottom:-40px; right:10%; animation-delay:2s; }
    @keyframes drift { 0%,100%{ transform:translateY(0);} 50%{ transform:translateY(-24px);} }
    .glass-card { background:#ffffff; border:1px solid #e2e8f0; border-radius:20px; box-shadow:0 10px 30px -10px rgba(15,23,42,.25); }
    .btn-gradient { background:linear-gradient(90deg,#6366f1,#8b5cf6); color:#fff; border:none; box-shadow:0 8px 22px -8px rgba(99,102,241,.6); transition:transform .2s ease, box-shadow .2s ease, filter .2s ease; }
    .btn-gradient:hover { transform:translateY(-2px); box-shadow:0 12px 28px -12px rgba(99,102,241,.7); filter:saturate(1.05); }
    .btn-soft { background:#eef2ff; color:#4338ca; border:1px solid #c7d2fe; transition:transform .2s ease, box-shadow .2s ease; }
    .btn-soft:hover { transform:translateY(-2px); box-shadow:0 10px 24px -14px rgba(67,56,202,.45); }
    .notif-item { 
        background:#ffffff; 
        border:1px solid #e5e7eb; 
        transition: transform .18s ease, background .18s ease, box-shadow .18s ease; 
    }
    .notif-item:hover { 
        transform:translateY(-2px); 
        box-shadow:0 14px 32px -12px rgba(0,0,0,.18); 
        background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
    }
    .notif-unread { border-left:6px solid #22c55e; }
    .notif-read { border-left:6px solid #94a3b8; opacity:.95; }
    .badge-dot { width:10px; height:10px; border-radius:50%; display:inline-block; margin-right:8px; }
    .dot-unread { background:#22c55e; }
    .dot-read { background:#94a3b8; }
    .anim-pop { animation: pop .35s ease; }
    @keyframes pop { 0%{ transform:scale(.96);} 100%{ transform:scale(1);} }
</style>
@endpush

@section('content')
<div class="hero-notifications py-5">
    <div class="float"></div>
    <div class="float"></div>
    <div class="container position-relative py-4">
        <div class="text-center text-white mb-4">
            <h1 class="fw-bold mb-2">Notificaciones</h1>
            <p class="opacity-75 mb-0">Mantente al día de lo importante</p>
        </div>
        <div class="glass-card p-3 p-md-4 mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-light text-dark border">Totales: {{ $counts['total'] }}</span>
                    <span class="badge bg-success">No leídas: {{ $counts['unread'] }}</span>
                    @if($unreadOnly)
                        <span class="badge bg-info text-dark">Filtro: No leídas</span>
                    @endif
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('notifications.index') }}" class="btn btn-soft btn-sm rounded-pill px-3"><i class="fas fa-list me-1"></i>Todas</a>
                    <a href="{{ route('notifications.index', ['unread' => 1]) }}" class="btn btn-soft btn-sm rounded-pill px-3"><i class="fas fa-envelope-open me-1"></i>No leídas</a>
                    <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-gradient btn-sm rounded-pill px-3" type="submit"><i class="fas fa-check-double me-1"></i>Marcar todas leídas</button>
                    </form>
                    <form action="{{ route('notifications.destroyAll') }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar todas las notificaciones?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm rounded-pill px-3" type="submit"><i class="fas fa-trash me-1"></i>Eliminar todas</button>
                    </form>
                </div>
            </div>
        </div>

        @if($notifications->isEmpty())
            <div class="glass-card p-5 text-center">
                <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                <div class="h5 text-muted mb-0">No hay notificaciones</div>
            </div>
        @else
            <div class="row g-3">
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data ?? [];
                        $title = $data['title'] ?? 'Notificación';
                        $message = $data['message'] ?? '';
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <div class="col-12">
                        <div class="notif-item anim-pop rounded-4 p-3 d-flex align-items-start gap-3 {{ $isUnread ? 'notif-unread' : 'notif-read' }}">
                            <div class="pt-1">
                                <span class="badge-dot {{ $isUnread ? 'dot-unread' : 'dot-read' }}"></span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="mb-1 fw-bold">{{ $title }}</h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                @if($message)
                                    <div class="text-muted">{!! nl2br(e($message)) !!}</div>
                                @endif
                                @if(!$isUnread)
                                    <small class="text-muted d-block mt-1">Leída: {{ $notification->read_at?->format('d/m/Y H:i') }}</small>
                                @endif
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @if($isUnread)
                                <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-soft btn-sm rounded-pill px-3" type="submit"><i class="fas fa-eye me-1"></i>Marcar leída</button>
                                </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta notificación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3" type="submit"><i class="fas fa-times me-1"></i>Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
