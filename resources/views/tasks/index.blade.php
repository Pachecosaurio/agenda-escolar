@extends('layouts.app')

@push('styles')
<style>
    /* CSS Variables */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --glass-bg: rgba(255, 255, 255, 0.25);
        --glass-border: rgba(255, 255, 255, 0.18);
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.2);
        --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.3);
        --transition-smooth: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        --border-radius: 20px;
    }

    /* Hero Section */
    .hero-section {
        background: var(--primary-gradient);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        padding: 0;
    }

    .hero-content {
        position: relative;
        z-index: 10;
        padding: 2rem 0;
    }

    /* Floating Elements */
    .floating-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .floating-circle {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        animation: float 6s ease-in-out infinite;
    }

    .floating-circle:nth-child(1) {
        width: 100px;
        height: 100px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .floating-circle:nth-child(2) {
        width: 150px;
        height: 150px;
        top: 60%;
        right: 15%;
        animation-delay: 2s;
    }

    .floating-circle:nth-child(3) {
        width: 80px;
        height: 80px;
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    /* Glass Effects */
    .glass-effect {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        box-shadow: var(--shadow-lg);
    }

    /* Text Effects */
    .text-glow {
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }

    .text-shadow {
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    /* Cards */
    .filter-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stats-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: var(--transition-smooth);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .content-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: var(--transition-smooth);
    }

    /* Action Buttons */
    .action-btn {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        border: none;
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
        background: linear-gradient(135deg, #f57c00 0%, #ef6c00 100%);
    }

    .action-button {
        position: relative;
        overflow: hidden;
        transition: var(--transition-smooth);
        border: none;
    }

    .action-button:hover {
        transform: translateY(-5px) scale(1.05);
    }

    .action-button.hovering {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% { transform: translateY(-5px) scale(1.05); }
        50% { transform: translateY(-7px) scale(1.08); }
        100% { transform: translateY(-5px) scale(1.05); }
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    /* Task Specific Styles */
    .task-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.8) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: var(--transition-smooth);
    }
    
    .task-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-lg);
        border-color: rgba(255, 152, 0, 0.3);
    }

    /* Mejorar títulos largos */
    .task-card .card-header {
        min-height: 80px;
        display: flex;
        align-items: center;
    }

    .task-card .card-header h5 {
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
        line-height: 1.3;
        margin-bottom: 0.5rem;
        max-width: 100%;
    }

    .task-card .card-header .flex-grow-1 {
        min-width: 0; /* Permite que el contenedor se contraiga */
    }

    .task-card .card-header .flex-shrink-0 {
        margin-left: 1rem;
    }

    /* Responsive para títulos */
    @media (max-width: 576px) {
        .task-card .card-header {
            min-height: 90px;
            padding: 1rem !important;
        }
        
        .task-card .card-header h5 {
            font-size: 1rem !important;
            line-height: 1.2;
        }
        
        .task-card .card-header .flex-shrink-0 {
            margin-left: 0.5rem;
        }
    }
    
    .priority-badge {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: white;
        font-weight: 600;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 0.8rem;
    }
    
    .status-badge {
        font-weight: 600;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 0.8rem;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
        color: #212529;
    }
    
    .status-completed {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .task-stats .stats-card {
        background: linear-gradient(135deg, rgba(255, 152, 0, 0.1) 0%, rgba(255, 152, 0, 0.05) 100%);
    }

    /* Icon wrapper */
    .icon-wrapper {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .with-ring {
        position: relative;
    }

    .with-ring::before {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        animation: ring-pulse 3s infinite;
    }

    @keyframes ring-pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(1.2);
            opacity: 0;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            min-height: auto;
            padding: 2rem 0;
        }
        
        .floating-circle {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <!-- Elementos flotantes decorativos -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    
    <!-- Decorative grid pattern -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Cdefs%3E%3Cpattern id=%22grid%22 width=%2210%22 height=%2210%22 patternUnits=%22userSpaceOnUse%22%3E%3Cpath d=%22M 10 0 L 0 0 0 10%22 fill=%22none%22 stroke=%22rgba(255,255,255,0.08)%22 stroke-width=%221%22/%3E%3C/pattern%3E%3C/defs%3E%3Crect width=%22100%22 height=%22100%22 fill=%22url(%23grid)%22/%3E%3C/svg%3E'); opacity: 0.4;"></div>
    
    <!-- Header -->
    <div class="hero-content">
        <div class="row justify-content-center py-5">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <div class="icon-wrapper with-ring mx-auto mb-4 glass-effect" style="width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid rgba(255, 255, 255, 0.4); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);">
                        <i class="fas fa-tasks text-white" style="font-size: 3.5rem; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                    </div>
                    <h1 class="text-white fw-bold mb-3 text-glow" style="font-size: 3rem; letter-spacing: 2px;">
                        Gestión de Tareas
                    </h1>
                    <p class="text-white-50 fs-5 mb-0" style="max-width: 600px; margin: 0 auto;">
                        Organiza y administra todas tus tareas académicas de manera eficiente
                    </p>
                </div>

                <!-- Filtros mejorados -->
                <div class="filter-card rounded-4 p-4 mb-5 shadow-lg">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="search" class="form-label fw-semibold text-dark">
                                <i class="fas fa-search text-primary me-2"></i>Buscar tareas
                            </label>
                            <input type="text" name="search" id="search" 
                                   class="form-control border-0 shadow-sm" 
                                   placeholder="Buscar por título..." 
                                   value="{{ request('search') }}"
                                   style="border-radius: 15px; background: rgba(248, 249, 250, 0.8); padding: 12px 16px;">
                        </div>
                        <div class="col-md-4">
                            <label for="due_date" class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar text-primary me-2"></i>Fecha de vencimiento
                            </label>
                            <input type="date" name="due_date" id="due_date" 
                                   class="form-control border-0 shadow-sm" 
                                   value="{{ request('due_date') }}"
                                   style="border-radius: 15px; background: rgba(248, 249, 250, 0.8); padding: 12px 16px;">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn action-btn text-white flex-fill rounded-pill py-2 px-4 fw-semibold">
                                    <i class="fas fa-filter me-2"></i>Filtrar
                                </button>
                                @if(request('search') || request('due_date'))
                                    <a href="{{ route('tasks.index') }}" class="btn btn-light rounded-pill py-2 px-3" title="Limpiar filtros">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Estadísticas mejoradas -->
                <div class="row g-4 mb-5 task-stats">
                    <div class="col-md-3">
                        <div class="stats-card border-0 rounded-4 text-center py-4 h-100">
                            <div class="card-body p-4 position-relative">
                                <div class="position-relative mb-3">
                                    <i class="fas fa-list-check text-white" style="font-size: 3rem; animation: pulse 2s infinite; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #ff9800, #f57c00); color: white; box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4);">
                                        {{ $tasks->count() }}
                                    </span>
                                </div>
                                <h5 class="fw-bold text-white mb-2 text-shadow">Total de Tareas</h5>
                                <small class="text-white" style="opacity: 0.9;">Todas las tareas registradas</small>
                                @if($tasks->count() > 0)
                                    <div class="mt-3">
                                        <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill">
                                            <i class="fas fa-check-circle me-1"></i> Activas
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card border-0 rounded-4 text-center py-4 h-100">
                            <div class="card-body p-4 position-relative">
                                <div class="position-relative mb-3">
                                    <i class="fas fa-clock text-white" style="font-size: 3rem; animation: pulse 2s infinite 0.5s; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #ffc107, #ff8f00); color: #212529; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);">
                                        {{ $tasks->where('completed', false)->count() }}
                                    </span>
                                </div>
                                <h5 class="fw-bold text-white mb-2 text-shadow">Pendientes</h5>
                                <small class="text-white" style="opacity: 0.9;">Tareas por completar</small>
                                @if($tasks->where('completed', false)->count() > 0)
                                    <div class="mt-3">
                                        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">
                                            <i class="fas fa-hourglass-half me-1"></i> En Progreso
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card border-0 rounded-4 text-center py-4 h-100">
                            <div class="card-body p-4 position-relative">
                                <div class="position-relative mb-3">
                                    <i class="fas fa-check-double text-white" style="font-size: 3rem; animation: pulse 2s infinite 1s; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);">
                                        {{ $tasks->where('completed', true)->count() }}
                                    </span>
                                </div>
                                <h5 class="fw-bold text-white mb-2 text-shadow">Completadas</h5>
                                <small class="text-white" style="opacity: 0.9;">Tareas finalizadas</small>
                                @if($tasks->where('completed', true)->count() > 0)
                                    <div class="mt-3">
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                            <i class="fas fa-trophy me-1"></i> Logradas
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card border-0 rounded-4 text-center py-4 h-100">
                            <div class="card-body p-4 position-relative">
                                <div class="position-relative mb-3">
                                    <i class="fas fa-exclamation-triangle text-white" style="font-size: 3rem; animation: pulse 2s infinite 1.5s; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #dc3545, #e83e8c); color: white; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);">
                                        {{ $tasks->where('due_date', '<', now())->where('completed', false)->count() }}
                                    </span>
                                </div>
                                <h5 class="fw-bold text-white mb-2 text-shadow">Vencidas</h5>
                                <small class="text-white" style="opacity: 0.9;">Tareas fuera de tiempo</small>
                                @if($tasks->where('due_date', '<', now())->where('completed', false)->count() > 0)
                                    <div class="mt-3">
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                            <i class="fas fa-alert-triangle me-1"></i> Urgente
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
                    <p class="text-white-50 mb-0">Gestiona tus tareas de manera eficiente</p>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('tasks.create') }}" class="btn action-btn action-button text-white btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                            <div class="btn-content">
                                <i class="fas fa-plus-circle fs-4 d-block mb-2"></i>
                                <div>Nueva Tarea</div>
                                <small class="opacity-75">Crear nueva tarea</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <a href="{{ route('tasks.export.excel') }}" class="btn btn-success action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                            <div class="btn-content">
                                <i class="fas fa-file-excel fs-4 d-block mb-2"></i>
                                <div>Excel</div>
                                <small class="opacity-75">Exportar hoja de cálculo</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <a href="{{ route('tasks.export.pdf') }}" class="btn btn-danger action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
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
                        <button onclick="refreshTasks()" class="btn btn-warning action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
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

        <!-- Lista de tareas -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-white mb-0" style="font-size: 1.8rem;">
                        <i class="fas fa-list me-2"></i>Lista de Tareas
                        @if(request('search') || request('due_date'))
                            <span class="badge bg-light text-dark ms-2">Filtrado</span>
                        @endif
                    </h2>
                    <div class="text-white-50">
                        <small>{{ $tasks->count() }} tarea(s) encontrada(s)</small>
                    </div>
                </div>
                
                <div class="row">
                    @forelse($tasks as $task)
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="task-card content-card border-0 rounded-4 shadow-lg h-100">
                                <!-- Header de la tarjeta -->
                                <div class="card-header border-0 rounded-top-4 position-relative" style="background: linear-gradient(135deg, {{ $task->completed ? '#28a745' : '#ff9800' }} 0%, {{ $task->completed ? '#20c997' : '#f57c00' }} 100%); overflow: hidden; min-height: 80px; padding: 1.25rem;">
                                    <!-- Efecto de brillo -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%); transform: translateX(-100%); transition: transform 0.6s;"></div>
                                    
                                    <div class="d-flex justify-content-between align-items-start position-relative">
                                        <div class="flex-grow-1 me-3">
                                            <h5 class="text-white fw-bold mb-2" style="font-size: 1.15rem; line-height: 1.3; word-wrap: break-word; overflow-wrap: break-word; hyphens: auto;">{{ $task->title }}</h5>
                                            <small class="text-white-50 d-block" style="font-size: 0.85rem; margin-top: 0.25rem;">
                                                @if($task->completed)
                                                    <i class="fas fa-check-circle me-1"></i>Tarea Completada
                                                @else
                                                    <i class="fas fa-clock me-1"></i>Tarea Pendiente
                                                @endif
                                            </small>
                                        </div>
                                        <div class="d-flex flex-column align-items-end gap-2 flex-shrink-0">
                                            <span class="badge bg-white" style="border-radius: 20px; padding: 8px 12px; color: {{ $task->completed ? '#28a745' : '#ff9800' }}; font-size: 0.75rem;">
                                                <i class="fas {{ $task->completed ? 'fa-check' : 'fa-clock' }}"></i>
                                            </span>
                                            
                                            <!-- Dropdown de acciones -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" style="width: 34px; height: 34px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-ellipsis-v" style="font-size: 0.8rem;"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                    <li><a class="dropdown-item" href="{{ route('tasks.edit', $task) }}">
                                                        <i class="fas fa-edit text-primary me-2"></i>Editar
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="confirmDeleteTask({{ $task->id }}, '{{ addslashes($task->title) }}')">
                                                        <i class="fas fa-trash text-danger me-2"></i>Eliminar
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body p-4">
                                    @if($task->description)
                                        <p class="text-dark mb-3" style="font-size: 0.9rem; line-height: 1.5;">
                                            {{ $task->description }}
                                        </p>
                                    @endif
                                    
                                    <!-- Información de fechas -->
                                    @if($task->due_date)
                                        <div class="row g-3 mb-3">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-calendar-alt text-warning"></i>
                                                    </div>
                                                    <div>
                                                        <small class="fw-semibold text-dark d-block">Fecha de Vencimiento</small>
                                                        <span class="badge {{ $task->due_date < now() && !$task->completed ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' }} px-3 py-2">
                                                            {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y H:i') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Información adicional -->
                                    <div class="row g-2 text-center">
                                        <div class="col-6">
                                            <div class="p-2 bg-light rounded-3">
                                                <small class="text-muted d-block">Estado</small>
                                                <strong style="font-size: 0.8rem;">
                                                    @if($task->completed)
                                                        <span class="text-success">Completada</span>
                                                    @elseif($task->due_date && $task->due_date < now())
                                                        <span class="text-danger">Vencida</span>
                                                    @else
                                                        <span class="text-warning">Pendiente</span>
                                                    @endif
                                                </strong>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 bg-light rounded-3">
                                                <small class="text-muted d-block">Creada</small>
                                                <strong style="font-size: 0.8rem;">{{ $task->created_at->format('d/m/Y') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer border-0 bg-transparent p-4 pt-0">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary btn-sm rounded-pill px-4 flex-fill fw-semibold">
                                            <i class="fas fa-edit me-1"></i>Editar
                                        </a>
                                        
                                        <button onclick="confirmDeleteTask({{ $task->id }}, '{{ $task->title }}')" 
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
                                    <i class="fas fa-tasks text-primary" style="font-size: 4rem; opacity: 0.7;"></i>
                                </div>
                                <h3 class="text-white mb-3 fw-bold">¡No hay tareas registradas!</h3>
                                <p class="text-white-50 mb-4 fs-5">Crea tu primera tarea para comenzar a organizar tu trabajo</p>
                                <a href="{{ route('tasks.create') }}" class="btn action-btn text-white btn-lg rounded-pill px-5 py-3 fw-semibold shadow-lg">
                                    <i class="fas fa-plus me-2"></i>Crear Primera Tarea
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <p id="deleteTaskMessage" class="mb-3"></p>
                <div class="alert alert-warning border-0 rounded-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Atención:</strong> Esta acción no se puede deshacer.
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteTaskForm" method="POST" style="display: inline;">
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
function confirmDeleteTask(taskId, taskTitle) {
    const modal = new bootstrap.Modal(document.getElementById('deleteTaskModal'));
    const form = document.getElementById('deleteTaskForm');
    const message = document.getElementById('deleteTaskMessage');
    
    // Escapar caracteres especiales en el título para mostrar correctamente
    const safeTitle = taskTitle.replace(/'/g, '&#39;').replace(/"/g, '&quot;');
    message.innerHTML = `¿Estás seguro de que deseas eliminar la tarea "<strong>${safeTitle}</strong>"?`;
    form.action = `{{ route('tasks.index') }}/${taskId}`;
    
    modal.show();
}

function refreshTasks() {
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
    const cards = document.querySelectorAll('.task-card');
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
            const rect = this.getBoundingClientRect();
            const x = e.clientX;
            const y = e.clientY;
            
            if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) {
                this.classList.add('hovering');
                
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
});
</script>
@endpush
@endsection
