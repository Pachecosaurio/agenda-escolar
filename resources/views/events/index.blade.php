{{-- Vista de Eventos: listado principal con filtros, estados y soporte de recurrencias. --}}
@extends('layouts.app')

@push('styles')
<style>
    /* Clases dinámicas para headers de eventos */
    .event-header-recurring {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        overflow: hidden;
    }
    
    .event-header-generated {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
        overflow: hidden;
    }
    
    .event-header-regular {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .icon-wrapper {
        animation: float 6s ease-in-out infinite;
        position: relative;
    }
    
    .icon-wrapper::before {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        background: conic-gradient(from 0deg, #667eea, #764ba2, #667eea);
        border-radius: 50%;
        z-index: -1;
        animation: rotate 3s linear infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        25% { transform: translateY(-10px) rotate(1deg); }
        50% { transform: translateY(-5px) rotate(-1deg); }
        75% { transform: translateY(-15px) rotate(0.5deg); }
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 8px 12px;
        border-radius: 20px;
    }
    
    .alert {
        font-size: 0.9rem;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    .btn:hover {
        transform: translateY(-3px);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .filter-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(30px);
        border: 2px solid rgba(255, 255, 255, 0.4);
        position: relative;
        overflow: hidden;
    }
    
    .filter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(45deg, #667eea, #764ba2, #667eea);
        background-size: 200% 100%;
        animation: gradient-shift 3s ease infinite;
    }
    
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .stats-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .stats-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.08) 100%);
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.8s;
    }
    
    .stats-card:hover::before {
        left: 100%;
    }
    
    .stats-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
        border-radius: 10px 10px 0 0;
    }
    
    .action-btn {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .action-btn:hover::before {
        left: 100%;
    }
    
    .action-btn:hover {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
    }
    
    .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
    }
    
    .floating-circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float-circle 20s infinite linear;
    }
    
    .floating-circle:nth-child(1) {
        width: 80px;
        height: 80px;
        left: 10%;
        animation-delay: 0s;
    }
    
    .floating-circle:nth-child(2) {
        width: 120px;
        height: 120px;
        left: 80%;
        animation-delay: 5s;
    }
    
    .floating-circle:nth-child(3) {
        width: 60px;
        height: 60px;
        left: 50%;
        animation-delay: 10s;
    }
    
    @keyframes float-circle {
        0% {
            transform: translateY(100vh) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            transform: translateY(-100px) rotate(360deg);
            opacity: 0;
        }
    }
    
    .event-card {
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .event-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }
    
    .event-card:hover::before {
        transform: translateX(100%);
    }
    
    .event-card:hover {
        border-color: rgba(102, 126, 234, 0.3);
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        border-color: rgba(102, 126, 234, 0.5);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .text-glow {
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }
    
    /* Estilos avanzados para botones de acciones rápidas */
    .action-button {
        position: relative;
        overflow: hidden;
        transform: translateY(0);
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 2px solid transparent;
        background-clip: padding-box;
    }
    
    .action-button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.25) 0%, transparent 70%);
        transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
        transform: translate(-50%, -50%);
        border-radius: 50%;
        z-index: 0;
        pointer-events: none;
    }
    
    .action-button:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .action-button:hover {
        transform: translateY(-8px) scale(1.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .action-button .btn-content {
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }
    
    .action-button:hover .btn-content {
        transform: scale(1.1);
    }
    
    .action-button:hover i {
        animation: bounce-icon 0.6s ease;
    }
    
    .action-button:active {
        transform: translateY(-4px) scale(1.02);
        transition: all 0.1s ease;
    }
    
    /* Efectos específicos por color */
    .btn-success.action-button:hover {
        background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%);
        box-shadow: 0 20px 40px rgba(40, 167, 69, 0.4);
    }
    
    .btn-danger.action-button:hover {
        background: linear-gradient(135deg, #dc3545 0%, #e83e8c 50%, #6f42c1 100%);
        box-shadow: 0 20px 40px rgba(220, 53, 69, 0.4);
    }
    
    .btn-info.action-button:hover {
        background: linear-gradient(135deg, #17a2b8 0%, #6610f2 50%, #6f42c1 100%);
        box-shadow: 0 20px 40px rgba(23, 162, 184, 0.4);
    }
    
    .btn-warning.action-button:hover {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 50%, #dc3545 100%);
        box-shadow: 0 20px 40px rgba(255, 193, 7, 0.4);
    }
    
    .action-btn.action-button:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
    }
    
    @keyframes bounce-icon {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        40% {
            transform: translateY(-10px) rotate(-5deg);
        }
        60% {
            transform: translateY(-5px) rotate(5deg);
        }
    }
    
    /* Efecto de ondas al hacer clic */
    .action-button::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
        z-index: 0;
        pointer-events: none;
    }
    
    .action-button:active::after {
        width: 200px;
        height: 200px;
        transition: width 0.1s, height 0.1s;
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<div class="container-fluid" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; position: relative; overflow: hidden;">
    <!-- Elementos flotantes decorativos -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    
    <!-- Decorative grid pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Cdefs%3E%3Cpattern id=%22grid%22 width=%2210%22 height=%2210%22 patternUnits=%22userSpaceOnUse%22%3E%3Cpath d=%22M 10 0 L 0 0 0 10%22 fill=%22none%22 stroke=%22rgba(255,255,255,0.08)%22 stroke-width=%221%22/%3E%3C/pattern%3E%3C/defs%3E%3Crect width=%22100%22 height=%22100%22 fill=%22url(%23grid)%22/%3E%3C/svg%3E'); opacity: 0.4;"></div>
    
    <!-- Header -->
    <div class="row justify-content-center py-5">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <div class="icon-wrapper mx-auto mb-4 glass-effect" style="width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid rgba(255, 255, 255, 0.4); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);">
                    <i class="fas fa-calendar-alt text-white" style="font-size: 3.5rem; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                </div>
                <h1 class="text-white fw-bold mb-3 text-glow" style="font-size: 3.5rem; letter-spacing: -1px;">
                    Gestión de Eventos
                </h1>
                <p class="text-white fs-5 mb-4" style="opacity: 0.9; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                    Organiza y programa todos tus eventos escolares con opciones avanzadas de repetición y un sistema intuitivo de gestión
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <span class="badge glass-effect text-white px-4 py-2" style="font-size: 0.9rem;">
                        <i class="fas fa-magic me-2"></i>Eventos Recurrentes
                    </span>
                    <span class="badge glass-effect text-white px-4 py-2" style="font-size: 0.9rem;">
                        <i class="fas fa-filter me-2"></i>Filtros Avanzados
                    </span>
                    <span class="badge glass-effect text-white px-4 py-2" style="font-size: 0.9rem;">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas en Tiempo Real
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y estadísticas -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="card filter-card border-0 rounded-4 shadow-lg mb-4">
                <div class="card-body p-4">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="search" class="form-label fw-semibold text-dark">
                                <i class="fas fa-search text-primary me-2"></i>Buscar eventos
                            </label>
                            <input type="text" name="search" id="search" 
                                   class="form-control border-0 shadow-sm" 
                                   placeholder="Buscar por título..." 
                                   value="{{ request('search') }}"
                                   style="border-radius: 15px; background: rgba(248, 249, 250, 0.8); padding: 12px 16px;">
                        </div>
                        <div class="col-md-4">
                            <label for="start" class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar text-primary me-2"></i>Fecha específica
                            </label>
                            <input type="date" name="start" id="start" 
                                   class="form-control border-0 shadow-sm" 
                                   value="{{ request('start') }}"
                                   style="border-radius: 15px; background: rgba(248, 249, 250, 0.8); padding: 12px 16px;">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn action-btn text-white flex-fill rounded-pill py-2 px-4 fw-semibold">
                                    <i class="fas fa-filter me-2"></i>Filtrar
                                </button>
                                @if(request('search') || request('start'))
                                    <a href="{{ route('events.index') }}" class="btn btn-light rounded-pill py-2 px-3" title="Limpiar filtros">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Estadísticas mejoradas -->
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card stats-card border-0 rounded-4 text-center py-4 h-100">
                        <div class="card-body p-4 position-relative">
                            <div class="position-relative mb-3">
                                <i class="fas fa-calendar-check text-white" style="font-size: 3rem; animation: pulse 2s infinite; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                                    {{ $events->where('parent_event_id', null)->where('is_recurring', false)->count() }}
                                </span>
                            </div>
                            <h5 class="fw-bold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Eventos Únicos</h5>
                            <small class="text-white" style="opacity: 0.9;">Eventos sin repetición programados</small>
                            @if($events->where('parent_event_id', null)->where('is_recurring', false)->count() > 0)
                                <div class="mt-3">
                                    <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i> Activos
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card border-0 rounded-4 text-center py-4 h-100">
                        <div class="card-body p-4 position-relative">
                            <div class="position-relative mb-3">
                                <i class="fas fa-repeat text-white" style="font-size: 3rem; animation: pulse 2s infinite 0.5s; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);">
                                    {{ $events->where('is_recurring', true)->where('parent_event_id', null)->count() }}
                                </span>
                            </div>
                            <h5 class="fw-bold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Series Recurrentes</h5>
                            <small class="text-white" style="opacity: 0.9;">Eventos principales con repetición</small>
                            @if($events->where('is_recurring', true)->where('parent_event_id', null)->count() > 0)
                                <div class="mt-3">
                                    <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                                        <i class="fas fa-sync-alt me-1"></i> Generando...
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card border-0 rounded-4 text-center py-4 h-100">
                        <div class="card-body p-4 position-relative">
                            <div class="position-relative mb-3">
                                <i class="fas fa-link text-white" style="font-size: 3rem; animation: pulse 2s infinite 1s; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #17a2b8, #138496); color: white; box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);">
                                    {{ $events->whereNotNull('parent_event_id')->count() }}
                                </span>
                            </div>
                            <h5 class="fw-bold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Eventos Generados</h5>
                            <small class="text-white" style="opacity: 0.9;">Instancias creadas automáticamente</small>
                            @if($events->whereNotNull('parent_event_id')->count() > 0)
                                <div class="mt-3">
                                    <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                        <i class="fas fa-robot me-1"></i> Auto-generados
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card border-0 rounded-4 text-center py-4 h-100">
                        <div class="card-body p-4 position-relative">
                            <div class="position-relative mb-3">
                                <i class="fas fa-calendar-day text-white" style="font-size: 3rem; animation: pulse 2s infinite 1.5s; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #ffc107, #fd7e14); color: #212529; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);">
                                    {{ $events->where('start', '>=', now())->count() }}
                                </span>
                            </div>
                            <h5 class="fw-bold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Próximos Eventos</h5>
                            <small class="text-white" style="opacity: 0.9;">Eventos programados para el futuro</small>
                            @if($events->where('start', '>=', now())->count() > 0)
                                <div class="mt-3">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                        <i class="fas fa-clock me-1"></i> Pendientes
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones principales mejoradas -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="text-center mb-4">
                <h3 class="text-white fw-bold mb-2" style="font-size: 1.5rem;">
                    <i class="fas fa-tools me-2"></i>Acciones Rápidas
                </h3>
                <p class="text-white-50 mb-0">Gestiona tus eventos de manera eficiente</p>
            </div>
            <div class="row g-3 justify-content-center">
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('events.create') }}" class="btn action-btn action-button text-white btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                        <div class="btn-content">
                            <i class="fas fa-plus-circle fs-4 d-block mb-2"></i>
                            <div>Nuevo Evento</div>
                            <small class="opacity-75">Crear evento simple o recurrente</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-2">
                    <a href="{{ route('events.export.excel') }}" class="btn btn-success action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                        <div class="btn-content">
                            <i class="fas fa-file-excel fs-4 d-block mb-2"></i>
                            <div>Excel</div>
                            <small class="opacity-75">Exportar hoja de cálculo</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-2">
                    <a href="{{ route('events.export.pdf') }}" class="btn btn-danger action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                        <div class="btn-content">
                            <i class="fas fa-file-pdf fs-4 d-block mb-2"></i>
                            <div>PDF</div>
                            <small class="opacity-75">Documento imprimible</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-2">
                    <a href="{{ route('calendar') }}" class="btn btn-info action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                        <div class="btn-content">
                            <i class="fas fa-calendar-alt fs-4 d-block mb-2"></i>
                            <div>Calendario</div>
                            <small class="opacity-75">Vista de calendario</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-2">
                    <button onclick="refreshEvents()" class="btn btn-warning action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                        <div class="btn-content">
                            <i class="fas fa-sync-alt fs-4 d-block mb-2"></i>
                            <div>Actualizar</div>
                            <small class="opacity-75">Refrescar datos</small>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Mensajes de estado -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            @if(session('success'))
                <div class="alert alert-success border-0 rounded-3 shadow-sm d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                    <div>
                        <strong>¡Éxito!</strong> {{ session('success') }}
                    </div>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3 shadow-sm">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        <strong>Se encontraron errores:</strong>
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Lista de eventos -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-white mb-0" style="font-size: 1.8rem;">
                    <i class="fas fa-list me-2"></i>Lista de Eventos
                    @if(request('search') || request('start'))
                        <span class="badge bg-light text-dark ms-2">Filtrado</span>
                    @endif
                </h2>
                <div class="text-white-50">
                    <small>{{ $events->count() }} evento(s) encontrado(s)</small>
                </div>
            </div>
            
            <div class="row">
                @forelse($events as $event)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card event-card border-0 rounded-4 shadow-lg h-100" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%); backdrop-filter: blur(20px); transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); border: 1px solid rgba(255,255,255,0.2);">
                            <!-- Header de la tarjeta con gradiente dinámico -->
                            @php
                                $headerClass = $event->isRecurringParent() ? 'event-header-recurring' : ($event->isGeneratedEvent() ? 'event-header-generated' : 'event-header-regular');
                            @endphp
                            <div class="card-header border-0 rounded-top-4 position-relative {{ $headerClass }}">
                                <!-- Efecto de brillo -->
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%); transform: translateX(-100%); transition: transform 0.6s;"></div>
                                
                                <div class="d-flex justify-content-between align-items-center position-relative">
                                    <div class="flex-grow-1">
                                        <h5 class="text-white fw-bold mb-1" style="font-size: 1.1rem;">{{ $event->title }}</h5>
                                        <small class="text-white-50">
                                            @if($event->isRecurringParent())
                                                <i class="fas fa-repeat me-1"></i>Evento Recurrente Principal
                                            @elseif($event->isGeneratedEvent())
                                                <i class="fas fa-link me-1"></i>Evento Generado
                                            @else
                                                <i class="fas fa-calendar me-1"></i>Evento Único
                                            @endif
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if($event->isRecurringParent())
                                            <span class="badge bg-white text-primary" title="Evento Recurrente Principal" style="border-radius: 20px; padding: 6px 10px;">
                                                <i class="fas fa-repeat"></i>
                                            </span>
                                        @elseif($event->isGeneratedEvent())
                                            <span class="badge bg-white text-info" title="Evento Generado" style="border-radius: 20px; padding: 6px 10px;">
                                                <i class="fas fa-link"></i>
                                            </span>
                                        @else
                                            <span class="badge bg-white text-dark" title="Evento Único" style="border-radius: 20px; padding: 6px 10px;">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                        @endif
                                        
                                        <!-- Dropdown de acciones rápidas -->
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" style="width: 32px; height: 32px; padding: 0;">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                <li><a class="dropdown-item" href="{{ route('events.edit', $event) }}">
                                                    <i class="fas fa-edit text-primary me-2"></i>Editar
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" 
                                                       data-event-id="{{ $event->id }}" 
                                                       data-event-title="{{ $event->title }}" 
                                                       data-is-recurring="{{ $event->isRecurringParent() ? 'true' : 'false' }}"
                                                       onclick="confirmDelete(this.dataset.eventId, this.dataset.eventTitle, this.dataset.isRecurring)">
                                                    <i class="fas fa-trash text-danger me-2"></i>Eliminar
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body p-4">
                                <p class="text-white mb-3" style="font-size: 0.9rem; line-height: 1.5; opacity: 0.9;">
                                    {{ $event->description ?: 'Sin descripción disponible' }}
                                </p>
                                
                                <!-- Información de fechas mejorada -->
                                <div class="row g-3 mb-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-play text-success"></i>
                                            </div>
                                            <div>
                                                <small class="fw-semibold text-white d-block" style="opacity: 0.8;">Fecha de Inicio</small>
                                                <span class="badge bg-success-subtle text-success px-3 py-2">
                                                    {{ $event->start ? $event->start->format('d/m/Y H:i') : '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($event->end)
                                    <div class="col-12">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-stop text-danger"></i>
                                            </div>
                                            <div>
                                                <small class="fw-semibold text-white d-block" style="opacity: 0.8;">Fecha de Fin</small>
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                                    {{ $event->end->format('d/m/Y H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                @if($event->isRecurringParent())
                                    <div class="alert alert-primary border-0 rounded-3 p-3 mb-3" style="background: rgba(102, 126, 234, 0.1); position: relative; overflow: hidden;">
                                        <div class="position-absolute top-0 start-0 w-100 h-1 bg-primary"></div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-repeat text-primary me-2"></i>
                                            <small class="fw-semibold text-primary">Configuración de Repetición</small>
                                        </div>
                                        <div class="row g-1 text-sm">
                                            <div class="col-12 mb-1">
                                                <small>
                                                    <strong>Frecuencia:</strong> 
                                                    @switch($event->recurrence_type)
                                                        @case('daily') 
                                                            <span class="badge bg-primary-subtle text-primary">Cada {{ $event->recurrence_interval }} día(s)</span>
                                                            @break
                                                        @case('weekly') 
                                                            <span class="badge bg-success-subtle text-success">Cada {{ $event->recurrence_interval }} semana(s)</span>
                                                            @break
                                                        @case('monthly') 
                                                            <span class="badge bg-warning-subtle text-warning">Cada {{ $event->recurrence_interval }} mes(es)</span>
                                                            @break
                                                        @case('yearly') 
                                                            <span class="badge bg-danger-subtle text-danger">Cada {{ $event->recurrence_interval }} año(s)</span>
                                                            @break
                                                    @endswitch
                                                </small>
                                            </div>
                                            @if($event->recurrence_end_date)
                                                <div class="col-12 mb-1">
                                                    <small><strong>Finaliza:</strong> {{ $event->recurrence_end_date->format('d/m/Y') }}</small>
                                                </div>
                                            @endif
                                            @if($event->recurrence_count)
                                                <div class="col-12 mb-1">
                                                    <small><strong>Repeticiones:</strong> {{ $event->recurrence_count }}</small>
                                                </div>
                                            @endif
                                            <div class="col-12">
                                                <small>
                                                    <strong>Eventos generados:</strong> 
                                                    <span class="badge bg-info-subtle text-info">{{ $event->childEvents()->count() }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($event->isGeneratedEvent() && $event->parentEvent)
                                    <div class="alert alert-info border-0 rounded-3 p-3 mb-3" style="background: rgba(23, 162, 184, 0.1);">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-link text-info me-2"></i>
                                            <div>
                                                <small class="text-info fw-semibold d-block">Evento Generado Automáticamente</small>
                                                <small class="text-muted">
                                                    Parte de la serie: "<strong>{{ $event->parentEvent->title }}</strong>"
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Información adicional -->
                                <div class="row g-2 text-center">
                                    <div class="col-6">
                                        <div class="p-2 rounded-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                                            <small class="text-white d-block" style="opacity: 0.8;">Creado</small>
                                            <strong class="text-white" style="font-size: 0.8rem;">{{ $event->created_at->format('d/m/Y') }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                                            <small class="text-white d-block" style="opacity: 0.8;">Estado</small>
                                            <strong style="font-size: 0.8rem;">
                                                @if($event->start > now())
                                                    <span class="text-info">Próximo</span>
                                                @elseif($event->end && $event->end < now())
                                                    <span class="text-warning">Finalizado</span>
                                                @else
                                                    <span class="text-success">En Curso</span>
                                                @endif
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer border-0 bg-transparent p-4 pt-0">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('events.edit', $event) }}" class="btn btn-primary btn-sm rounded-pill px-4 flex-fill fw-semibold">
                                        <i class="fas fa-edit me-1"></i>Editar
                                    </a>
                                    
                                    <button data-event-id="{{ $event->id }}" 
                                            data-event-title="{{ $event->title }}" 
                                            data-is-recurring="{{ $event->isRecurringParent() ? 'true' : 'false' }}"
                                            onclick="confirmDelete(this.dataset.eventId, this.dataset.eventTitle, this.dataset.isRecurring)"
                                            class="btn btn-outline-danger btn-sm rounded-pill px-4 flex-fill fw-semibold">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="icon-wrapper mx-auto mb-4 glass-effect" style="width: 150px; height: 150px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-plus text-primary" style="font-size: 4rem; opacity: 0.7;"></i>
                            </div>
                            <h3 class="text-white mb-3 fw-bold">¡No hay eventos registrados!</h3>
                            <p class="text-white-50 mb-4 fs-5">Crea tu primer evento para comenzar a organizar tu agenda escolar</p>
                            <a href="{{ route('events.create') }}" class="btn action-btn text-white btn-lg rounded-pill px-5 py-3 fw-semibold shadow-lg">
                                <i class="fas fa-plus me-2"></i>Crear Primer Evento
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <p id="deleteMessage" class="mb-3"></p>
                <div class="alert alert-warning border-0 rounded-3" id="recurringWarning" style="display: none;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Atención:</strong> Al eliminar este evento recurrente, también se eliminarán todos los eventos generados automáticamente.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">
                        <i class="fas fa-trash me-2"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(eventId, eventTitle, isRecurring) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    const message = document.getElementById('deleteMessage');
    const warning = document.getElementById('recurringWarning');
    
    message.textContent = `¿Estás seguro de que deseas eliminar el evento "${eventTitle}"?`;
    form.action = `{{ route('events.index') }}/${eventId}`;
    
    if (isRecurring) {
        warning.style.display = 'block';
    } else {
        warning.style.display = 'none';
    }
    
    modal.show();
}

function refreshEvents() {
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
    
    // Animación de rotación
    icon.style.animation = 'none';
    setTimeout(() => {
        icon.style.animation = 'spin 1s linear infinite';
    }, 10);
    
    // Simular recarga
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Agregar animación de spin
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

// Efectos adicionales cuando la página carga
document.addEventListener('DOMContentLoaded', function() {
    // Animación de entrada para las tarjetas
    const cards = document.querySelectorAll('.event-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Efecto hover para las tarjetas
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const headerEffect = this.querySelector('.card-header > div');
            if (headerEffect) {
                headerEffect.style.transform = 'translateX(100%)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const headerEffect = this.querySelector('.card-header > div');
            if (headerEffect) {
                setTimeout(() => {
                    headerEffect.style.transform = 'translateX(-100%)';
                }, 200);
            }
        });
    });
    
    // Mejorar animaciones de botones de acciones rápidas
    const actionButtons = document.querySelectorAll('.action-button');
    
    actionButtons.forEach(button => {
        // Detectar mouse enter con mayor precisión
        button.addEventListener('mouseenter', function(e) {
            // Verificar que el cursor realmente esté dentro del área del botón
            const rect = this.getBoundingClientRect();
            const x = e.clientX;
            const y = e.clientY;
            
            if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) {
                this.classList.add('hovering');
                
                // Agregar efecto de partículas
                createParticleEffect(this);
                
                // Vibración sutil
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            }
        });
        
        button.addEventListener('mouseleave', function() {
            this.classList.remove('hovering');
        });
        
        // Efecto de clic mejorado
        button.addEventListener('mousedown', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            // Crear efecto de ondas en la posición del clic
            const ripple = document.createElement('div');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.6)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = (x - 10) + 'px';
            ripple.style.top = (y - 10) + 'px';
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            ripple.style.pointerEvents = 'none';
            ripple.style.zIndex = '1000';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
        
        // Animación de entrada escalonada para los botones
        button.style.opacity = '0';
        button.style.transform = 'translateY(50px)';
        
        setTimeout(() => {
            button.style.transition = 'all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1)';
            button.style.opacity = '1';
            button.style.transform = 'translateY(0)';
        }, Array.from(actionButtons).indexOf(button) * 150);
    });
    
    // Función para crear efecto de partículas
    function createParticleEffect(button) {
        const particles = 6;
        const rect = button.getBoundingClientRect();
        
        for (let i = 0; i < particles; i++) {
            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.width = '4px';
            particle.style.height = '4px';
            particle.style.background = 'rgba(255, 255, 255, 0.8)';
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '9999';
            
            const startX = rect.left + rect.width / 2;
            const startY = rect.top + rect.height / 2;
            
            particle.style.left = startX + 'px';
            particle.style.top = startY + 'px';
            
            document.body.appendChild(particle);
            
            const angle = (i / particles) * Math.PI * 2;
            const distance = 50 + Math.random() * 30;
            const endX = startX + Math.cos(angle) * distance;
            const endY = startY + Math.sin(angle) * distance;
            
            particle.animate([
                { 
                    transform: 'translate(0, 0) scale(1)', 
                    opacity: 1 
                },
                { 
                    transform: `translate(${endX - startX}px, ${endY - startY}px) scale(0)`, 
                    opacity: 0 
                }
            ], {
                duration: 800,
                easing: 'cubic-bezier(0.25, 0.8, 0.25, 1)'
            }).onfinish = () => particle.remove();
        }
    }
});

// Agregar estilos para el efecto ripple
const rippleStyle = document.createElement('style');
rippleStyle.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .action-button.hovering {
        animation: gentle-pulse 2s ease-in-out infinite;
    }
    
    @keyframes gentle-pulse {
        0%, 100% {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        50% {
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }
    }
`;
document.head.appendChild(rippleStyle);
</script>
@endpush
@endsection
