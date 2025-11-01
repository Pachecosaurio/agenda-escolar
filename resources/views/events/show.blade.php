{{-- Vista de detalle de Evento. --}}
@extends('layouts.app')

@push('styles')
<style>
    .hero-event { background:linear-gradient(135deg,#667eea,#764ba2); position:relative; overflow:hidden; }
    .hero-event .floating { position:absolute; border-radius:50%; background:rgba(255,255,255,.12); backdrop-filter:blur(12px); animation: drift 12s ease-in-out infinite; }
    .hero-event .floating:nth-child(1){ width:140px; height:140px; top:10%; left:8%; animation-delay:0s; }
    .hero-event .floating:nth-child(2){ width:220px; height:220px; bottom:-40px; right:10%; animation-delay:4s; }
    .hero-event .floating:nth-child(3){ width:90px; height:90px; top:55%; left:22%; animation-delay:2s; }
    @keyframes drift { 0%,100%{ transform:translateY(0) rotate(0deg);} 50%{ transform:translateY(-35px) rotate(160deg);} }
    .glass-card { background:rgba(255,255,255,.85); border:1px solid #e2e8f0; box-shadow:0 10px 30px -8px rgba(15,23,42,.25); border-radius:26px; backdrop-filter:blur(18px); }
    .icon-ring { position:relative; width:120px; height:120px; display:flex; align-items:center; justify-content:center; border-radius:50%; background:rgba(255,255,255,.18); border:3px solid rgba(255,255,255,.45); box-shadow:0 16px 42px -10px rgba(0,0,0,.35); }
    .icon-ring:before { content:''; position:absolute; inset:-14px; border:2px solid rgba(255,255,255,.35); border-radius:50%; animation:ringPulse 6s linear infinite; }
    @keyframes ringPulse { 0%{transform:scale(1); opacity:1;} 100%{ transform:scale(1.25); opacity:0; } }
</style>
@endpush

@section('content')
<div class="hero-event py-5">
    <div class="floating"></div>
    <div class="floating"></div>
    <div class="floating"></div>
    <div class="container position-relative py-5">
        <div class="text-center mb-5 text-white">
            <div class="icon-ring mx-auto mb-4">
                <i class="fas fa-calendar-alt fa-3x text-white"></i>
            </div>
            <h1 class="fw-bold text-shadow" style="letter-spacing:1px;">Detalle del Evento</h1>
            <p class="lead mb-0 opacity-75">Revisa información y gestiona este evento</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card p-4 p-md-5 mb-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
                        <div>
                            <h2 class="mb-3 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-circle text-primary small"></i>
                                {{ $event->title }}
                            </h2>
                            <div class="d-flex flex-wrap gap-3 small text-muted">
                                <div><i class="far fa-calendar me-1 text-primary"></i><strong>Inicio:</strong> {{ $event->start ? \Carbon\Carbon::parse($event->start)->format('d/m/Y H:i') : '—' }}</div>
                                <div><i class="far fa-clock me-1 text-primary"></i><strong>Fin:</strong> {{ $event->end ? \Carbon\Carbon::parse($event->end)->format('d/m/Y H:i') : '—' }}</div>
                                <div><i class="fas fa-hashtag me-1 text-primary"></i>ID: {{ $event->id }}</div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('events.edit',$event) }}" class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2"><i class="fas fa-edit"></i><span>Editar</span></a>
                            <a href="{{ route('events.index') }}" class="btn btn-light rounded-pill px-4 d-flex align-items-center gap-2"><i class="fas fa-arrow-left"></i><span>Volver</span></a>
                            <form action="{{ route('events.destroy',$event) }}" method="POST" onsubmit="return confirm('¿Eliminar este evento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger rounded-pill px-4 d-flex align-items-center gap-2"><i class="fas fa-trash"></i><span>Eliminar</span></button>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-4">
                        <div class="col-md-8">
                            <h5 class="fw-semibold text-uppercase small text-muted mb-3">Descripción</h5>
                            <div class="p-3 rounded-3 border bg-white small" style="min-height:140px; line-height:1.5;">
                                {!! nl2br(e($event->description ?? 'Sin descripción proporcionada.')) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5 class="fw-semibold text-uppercase small text-muted mb-3">Información</h5>
                            <ul class="list-group small shadow-sm">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Creado</span>
                                    <span>{{ $event->created_at?->format('d/m/Y H:i') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Actualizado</span>
                                    <span>{{ $event->updated_at?->format('d/m/Y H:i') }}</span>
                                </li>
                                @if($event->user_id)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Usuario</span>
                                    <span>{{ $event->user?->name ?? '—' }}</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="text-center pb-4">
                    <a href="{{ route('calendar') }}" class="btn btn-outline-primary rounded-pill px-5"><i class="fas fa-calendar-alt me-2"></i>Ir al Calendario</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
