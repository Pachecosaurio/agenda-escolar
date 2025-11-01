@extends('layouts.app')

@push('styles')
<style>
    .home-hero { background:linear-gradient(135deg,#667eea 0%,#764ba2 50%,#a855f7 100%); position:relative; overflow:hidden; }
    .home-hero .floating-circle { position:absolute; border-radius:50%; background:rgba(255,255,255,0.12); animation: floatHome 24s linear infinite; }
    .home-hero .floating-circle:nth-child(1){ width:130px; height:130px; left:12%; animation-delay:0s; }
    .home-hero .floating-circle:nth-child(2){ width:190px; height:190px; left:75%; animation-delay:6s; }
    .home-hero .floating-circle:nth-child(3){ width:110px; height:110px; left:48%; animation-delay:12s; }
    .home-hero .floating-circle:nth-child(4){ width:150px; height:150px; left:30%; animation-delay:3s; }
    @keyframes floatHome { 0%{ transform:translateY(100vh) rotate(0deg); opacity:0;} 10%{opacity:1;} 90%{opacity:1;} 100%{ transform:translateY(-160px) rotate(360deg); opacity:0;} }

    .glass-hero-card { background:linear-gradient(135deg,rgba(255,255,255,0.18),rgba(255,255,255,0.06)); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,.32); border-radius:32px; position:relative; overflow:hidden; padding:3rem 2.75rem 2.75rem; }
    .glass-hero-card:before { content:""; position:absolute; top:0; left:0; right:0; height:5px; background:linear-gradient(90deg,#667eea,#764ba2,#a855f7,#667eea); background-size:300% 100%; animation:gradientShift 7s ease infinite; }
    @keyframes gradientShift {0%,100%{background-position:0% 50%;}50%{background-position:100% 50%;}}

    .home-title { font-weight:700; letter-spacing:-.5px; color:#fff; }
    .home-lead { color:rgba(255,255,255,0.8); font-size:1.15rem; line-height:1.55; }

    .feature-grid .feature-card, .quick-actions .feature-card, .stats-grid .stats-card { background:linear-gradient(135deg,rgba(255,255,255,0.22),rgba(255,255,255,0.10)); backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.35); border-radius:22px; padding:1.6rem 1.35rem 1.45rem; height:100%; position:relative; overflow:hidden; transition:all .35s ease; }
    .feature-card:hover, .stats-card:hover { transform:translateY(-6px); box-shadow:0 12px 30px -8px rgba(76,29,149,0.45); }

    .card-icon { width:70px; height:70px; border-radius:18px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 1rem; color:#fff; box-shadow:0 6px 16px -4px rgba(0,0,0,0.35); position:relative; }
    .card-icon.tasks { background:linear-gradient(135deg,#f59e0b,#f97316); }
    .card-icon.events { background:linear-gradient(135deg,#8b5cf6,#7c3aed); }
    .card-icon.calendar { background:linear-gradient(135deg,#0ea5e9,#2563eb); }
    .card-icon.payments { background:linear-gradient(135deg,#16a34a,#059669); }

    .stats-number { font-size:2.1rem; font-weight:700; margin-bottom:.35rem; letter-spacing:-1px; }
    .stats-card.tasks .stats-number { color:#f59e0b; }
    .stats-card.events .stats-number { color:#8b5cf6; }
    .stats-card.payments .stats-number { color:#16a34a; }

    .glass-btn { border:none; border-radius:14px; font-weight:600; padding:.85rem 1.35rem; display:inline-flex; align-items:center; gap:.45rem; letter-spacing:.3px; backdrop-filter:blur(10px); transition:all .35s; position:relative; overflow:hidden; }
    .glass-btn:before { content:""; position:absolute; inset:0; background:linear-gradient(135deg,rgba(255,255,255,0.25),rgba(255,255,255,0.05)); opacity:0; transition:opacity .35s; }
    .glass-btn:hover:before { opacity:1; }
    .glass-btn.primary { background:linear-gradient(90deg,#6366f1,#8b5cf6); color:#fff; }
    .glass-btn.success { background:linear-gradient(90deg,#16a34a,#059669); color:#fff; }
    .glass-btn.warning { background:linear-gradient(90deg,#f59e0b,#f97316); color:#fff; }
    .glass-btn.secondary { background:linear-gradient(90deg,#8b5cf6,#7c3aed); color:#fff; }

    .fade-in-up { opacity:0; transform:translateY(24px); animation:fadeInUp .85s ease forwards; }
    .fade-in-up:nth-child(2){ animation-delay:.07s; }
    .fade-in-up:nth-child(3){ animation-delay:.14s; }
    .fade-in-up:nth-child(4){ animation-delay:.21s; }
    @keyframes fadeInUp { to { opacity:1; transform:translateY(0);} }

    /* Replace dark body background with light neutral gradient wrapper */
    body { background:#f4f6fb; }
    .page-wrapper-bg { background:linear-gradient(180deg,#f4f6fb 0%,#eef2f9 100%); min-height:100%; }
    .home-hero { border-bottom:6px solid rgba(255,255,255,0.35); }
    footer, .app-footer { background:transparent; }
    .home-hero .container-fluid { position:relative; z-index:2; }
    .home-hero .gradient-overlay { position:absolute; inset:0; background:radial-gradient(circle at 30% 20%,rgba(255,255,255,0.18),transparent 60%), radial-gradient(circle at 70% 65%,rgba(255,255,255,0.12),transparent 55%); }

    @media (max-width: 768px) {
        .glass-hero-card { padding:2.25rem 1.6rem 2.1rem; }
        .home-title { font-size:1.85rem; }
        .card-icon { width:60px; height:60px; }
        .stats-number { font-size:1.85rem; }
    }
</style>
@endpush

@section('content')
<div class="page-wrapper-bg">
<div class="home-hero py-5">
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="gradient-overlay"></div>
    <div class="container-fluid py-4">
        @guest
        <div class="row justify-content-center mb-5">
            <div class="col-xl-8 col-lg-9">
                <div class="glass-hero-card mb-5 fade-in-up">
                    <div class="d-flex align-items-center mb-4 gap-3 flex-wrap">
                        <div class="card-icon calendar" style="margin:0;">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <h1 class="home-title mb-2">¡Bienvenido a tu Agenda Escolar!</h1>
                            <p class="home-lead mb-0">Organiza tareas, eventos y pagos con una experiencia moderna y unificada.</p>
                        </div>
                    </div>
                    <p class="text-white-50 mb-4" style="max-width:680px;">Regístrate y comienza a planificar tu éxito académico con herramientas intuitivas y métricas claras para tu progreso.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="glass-btn primary"><i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="glass-btn success"><i class="fa-solid fa-user-plus"></i> Registrarse Gratis</a>
                    </div>
                </div>
            </div>
        </div>
        @endguest

        @auth
        <div class="row justify-content-center mb-5">
            <div class="col-xl-9 col-lg-10">
                <div class="glass-hero-card mb-5 fade-in-up">
                    <div class="d-flex align-items-center mb-4 gap-3 flex-wrap">
                        <div class="card-icon calendar" style="margin:0;">
                            <i class="fa-solid fa-house"></i>
                        </div>
                        <div>
                            <h1 class="home-title mb-2">¡Bienvenido de nuevo, {{ Auth::user()->name }}!</h1>
                            <p class="home-lead mb-0">Resumen rápido de tu actividad y accesos inmediatos.</p>
                        </div>
                    </div>
                    <p class="text-white-50 mb-0" style="max-width:720px;">Mantén el ritmo: crea tareas, agenda eventos y registra pagos en segundos.</p>
                </div>
            </div>
        </div>
        @endauth

        @auth
        <div class="row stats-grid g-4 mb-5 justify-content-center">
            <div class="col-md-4 col-sm-6 fade-in-up">
                <div class="stats-card tasks text-center h-100">
                    <div class="card-icon tasks"><i class="fa-solid fa-list-check"></i></div>
                    <div class="stats-number">{{ Auth::user()->tasks()->count() }}</div>
                    <h6 class="fw-semibold text-white-50 mb-1">Tareas Totales</h6>
                    <small class="text-white-50">{{ Auth::user()->tasks()->where('completed', false)->count() }} pendientes</small>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 fade-in-up">
                <div class="stats-card events text-center h-100">
                    <div class="card-icon events"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="stats-number">{{ Auth::user()->events()->count() }}</div>
                    <h6 class="fw-semibold text-white-50 mb-1">Eventos Creados</h6>
                    <small class="text-white-50">Este mes</small>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 fade-in-up">
                <div class="stats-card payments text-center h-100">
                    <div class="card-icon payments"><i class="fa-solid fa-money-bill-wave"></i></div>
                    <div class="stats-number">${{ number_format(Auth::user()->payments()->sum('amount'), 0) }}</div>
                    <h6 class="fw-semibold text-white-50 mb-1">Total Pagado</h6>
                    <small class="text-white-50">Colegiatura</small>
                </div>
            </div>
        </div>
        @endauth

        <div class="row feature-grid g-4 quick-actions justify-content-center">
            @auth
            <div class="col-md-6 col-lg-3 col-sm-6 fade-in-up">
                <div class="feature-card text-center h-100">
                    <div class="card-icon tasks"><i class="fa-solid fa-plus"></i></div>
                    <h5 class="fw-bold mb-2 text-white">Nueva Tarea</h5>
                    <p class="text-white-50 mb-4" style="min-height:56px;">Crea una nueva tarea con fecha límite y detalles.</p>
                    <a href="{{ route('tasks.create') }}" class="glass-btn warning w-100 justify-content-center"><i class="fa-solid fa-plus"></i> Crear</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-sm-6 fade-in-up">
                <div class="feature-card text-center h-100">
                    <div class="card-icon events"><i class="fa-solid fa-calendar-plus"></i></div>
                    <h5 class="fw-bold mb-2 text-white">Nuevo Evento</h5>
                    <p class="text-white-50 mb-4" style="min-height:56px;">Agenda un evento importante en tu calendario.</p>
                    <a href="{{ route('events.create') }}" class="glass-btn secondary w-100 justify-content-center"><i class="fa-solid fa-calendar-plus"></i> Programar</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-sm-6 fade-in-up">
                <div class="feature-card text-center h-100">
                    <div class="card-icon calendar"><i class="fa-solid fa-eye"></i></div>
                    <h5 class="fw-bold mb-2 text-white">Ver Calendario</h5>
                    <p class="text-white-50 mb-4" style="min-height:56px;">Visualiza todas tus actividades en el calendario.</p>
                    <a href="{{ route('calendar') }}" class="glass-btn primary w-100 justify-content-center"><i class="fa-solid fa-calendar"></i> Abrir</a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-sm-6 fade-in-up">
                <div class="feature-card text-center h-100">
                    <div class="card-icon payments"><i class="fa-solid fa-receipt"></i></div>
                    <h5 class="fw-bold mb-2 text-white">Nuevo Pago</h5>
                    <p class="text-white-50 mb-4" style="min-height:56px;">Registra un pago de colegiatura o gasto escolar.</p>
                    <a href="{{ route('payments.create') }}" class="glass-btn success w-100 justify-content-center"><i class="fa-solid fa-plus"></i> Registrar</a>
                </div>
            </div>
            @endauth

            @guest
            <div class="col-md-6 col-lg-3 col-sm-6 fade-in-up">
                <div class="feature-card text-center h-100">
                    <div class="card-icon tasks"><i class="fa-solid fa-list-check"></i></div>
                    <h5 class="fw-bold mb-2 text-white">Gestión de Tareas</h5>
                    <p class="text-white-50 mb-4" style="min-height:56px;">Crea, organiza y completa tareas escolares fácilmente.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-sm-6 fade-in-up">
                <div class="feature-card text-center h-100">
                    <div class="card-icon events"><i class="fa-solid fa-calendar-days"></i></div>
                    <h5 class="fw-bold mb-2 text-white">Eventos Escolares</h5>
                    <p class="text-white-50 mb-4" style="min-height:56px;">Programa y visualiza eventos importantes.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-sm-6 fade-in-up">
                <div class="feature-card text-center h-100">
                    <div class="card-icon calendar"><i class="fa-solid fa-calendar"></i></div>
                    <h5 class="fw-bold mb-2 text-white">Calendario Unificado</h5>
                    <p class="text-white-50 mb-4" style="min-height:56px;">Todo en un solo lugar con filtros.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 col-sm-6 fade-in-up">
                <div class="feature-card text-center h-100">
                    <div class="card-icon payments"><i class="fa-solid fa-money-bill-wave"></i></div>
                    <h5 class="fw-bold mb-2 text-white">Control de Pagos</h5>
                    <p class="text-white-50 mb-4" style="min-height:56px;">Registro completo de colegiatura.</p>
                </div>
            </div>
            @endguest
        </div>
    </div>
</div>
</div>
@endsection
