@extends('layouts.app')

@section('content')

@guest
<!-- Vista para usuarios no autenticados -->
<div class="home-background">
    <div class="home-content">
        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <!-- Hero Section -->
                    <div class="hero-card fade-in-up">
                        <div class="hero-icon">
                            <i class="fa-solid fa-calendar-check fa-2x text-white"></i>
                        </div>
                        <h1 class="welcome-title">
                            ¡Bienvenido a tu Agenda Escolar!
                        </h1>
                        <p class="lead mb-4" style="color: #667; font-size: 1.25rem; line-height: 1.6;">
                            Organiza tus tareas, eventos y pagos de colegiatura de manera eficiente.<br>
                            <span class="fw-semibold">Regístrate ahora y comienza a planificar tu éxito académico.</span>
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                            <a href="{{ route('login') }}" class="glass-btn primary">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}" class="glass-btn success">
                                <i class="fa-solid fa-user-plus me-2"></i>
                                Registrarse Gratis
                            </a>
                        </div>
                    </div>

                    <!-- Features Grid -->
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3 fade-in-up">
                            <div class="feature-card text-center">
                                <div class="card-icon tasks">
                                    <i class="fa-solid fa-list-check"></i>
                                </div>
                                <h5 class="fw-bold mb-3" style="color: #1565c0;">Gestión de Tareas</h5>
                                <p class="text-muted">Crea, organiza y completa tus tareas escolares con fechas límite y archivos adjuntos.</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 fade-in-up">
                            <div class="feature-card text-center">
                                <div class="card-icon events">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </div>
                                <h5 class="fw-bold mb-3" style="color: #8e44ad;">Eventos Escolares</h5>
                                <p class="text-muted">Programa y visualiza eventos importantes en un calendario interactivo y moderno.</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 fade-in-up">
                            <div class="feature-card text-center">
                                <div class="card-icon calendar">
                                    <i class="fa-solid fa-calendar"></i>
                                </div>
                                <h5 class="fw-bold mb-3" style="color: #2980b9;">Calendario Unificado</h5>
                                <p class="text-muted">Visualiza todas tus actividades en un solo lugar con filtros avanzados.</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 fade-in-up">
                            <div class="feature-card text-center">
                                <div class="card-icon payments">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                                <h5 class="fw-bold mb-3" style="color: #27ae60;">Control de Pagos</h5>
                                <p class="text-muted">Lleva un registro completo de tus pagos de colegiatura y gastos escolares.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

@auth
<!-- Vista para usuarios autenticados -->
<div class="home-background">
    <div class="home-content">
        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <!-- Welcome Back Section -->
                    <div class="hero-card fade-in-up">
                        <div class="hero-icon">
                            <i class="fa-solid fa-house fa-2x text-white"></i>
                        </div>
                        <h1 class="welcome-title">
                            ¡Bienvenido de nuevo, {{ Auth::user()->name }}!
                        </h1>
                        <p class="lead mb-4" style="color: #667; font-size: 1.2rem; line-height: 1.6;">
                            Aquí tienes un resumen de tu actividad y accesos rápidos a todas tus herramientas.
                        </p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-4 fade-in-up">
                            <div class="stats-card tasks">
                                <div class="card-icon tasks mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    <i class="fa-solid fa-list-check"></i>
                                </div>
                                <div class="stats-number text-warning">{{ Auth::user()->tasks()->count() }}</div>
                                <h6 class="fw-semibold text-muted">Tareas Totales</h6>
                                <small class="text-muted">{{ Auth::user()->tasks()->where('completed', false)->count() }} pendientes</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4 fade-in-up">
                            <div class="stats-card events">
                                <div class="card-icon events mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </div>
                                <div class="stats-number" style="color: #8e44ad;">{{ Auth::user()->events()->count() }}</div>
                                <h6 class="fw-semibold text-muted">Eventos Creados</h6>
                                <small class="text-muted">Este mes</small>
                            </div>
                        </div>
                        
                        <div class="col-md-4 fade-in-up">
                            <div class="stats-card payments">
                                <div class="card-icon payments mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                                <div class="stats-number text-success">${{ number_format(Auth::user()->payments()->sum('amount'), 0) }}</div>
                                <h6 class="fw-semibold text-muted">Total Pagado</h6>
                                <small class="text-muted">Colegiatura</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3 fade-in-up">
                            <div class="feature-card text-center">
                                <div class="card-icon tasks">
                                    <i class="fa-solid fa-plus"></i>
                                </div>
                                <h5 class="fw-bold mb-3" style="color: #ff9500;">Nueva Tarea</h5>
                                <p class="text-muted mb-4">Crea una nueva tarea con fecha límite y detalles.</p>
                                <a href="{{ route('tasks.create') }}" class="glass-btn warning">
                                    <i class="fa-solid fa-plus me-1"></i> Crear
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 fade-in-up">
                            <div class="feature-card text-center">
                                <div class="card-icon events">
                                    <i class="fa-solid fa-calendar-plus"></i>
                                </div>
                                <h5 class="fw-bold mb-3" style="color: #8e44ad;">Nuevo Evento</h5>
                                <p class="text-muted mb-4">Agenda un evento importante en tu calendario.</p>
                                <a href="{{ route('events.create') }}" class="glass-btn secondary">
                                    <i class="fa-solid fa-calendar-plus me-1"></i> Programar
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 fade-in-up">
                            <div class="feature-card text-center">
                                <div class="card-icon calendar">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                                <h5 class="fw-bold mb-3" style="color: #2980b9;">Ver Calendario</h5>
                                <p class="text-muted mb-4">Visualiza todas tus actividades en el calendario.</p>
                                <a href="{{ route('calendar') }}" class="glass-btn primary">
                                    <i class="fa-solid fa-calendar me-1"></i> Abrir
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3 fade-in-up">
                            <div class="feature-card text-center">
                                <div class="card-icon payments">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <h5 class="fw-bold mb-3" style="color: #27ae60;">Nuevo Pago</h5>
                                <p class="text-muted mb-4">Registra un pago de colegiatura o gasto escolar.</p>
                                <a href="{{ route('payments.create') }}" class="glass-btn success">
                                    <i class="fa-solid fa-plus me-1"></i> Registrar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endauth
@endsection
