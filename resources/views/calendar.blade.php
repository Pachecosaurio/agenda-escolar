@extends('layouts.app')

@push('styles')
<!-- Estilos específicos del calendario (FullCalendar CSS ya cargado globalmente) -->
<style>
    /* Calendar CSS Variables */
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

    /* Estilos mejorados para FullCalendar - Eventos visibles */
    .fc-event-title {
        font-weight: 600 !important;
        color: white !important;
        font-size: 12px !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5) !important;
    }
    
    .fc-event-time {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500 !important;
        font-size: 11px !important;
    }
    
    .fc-daygrid-event {
        border-radius: 6px !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        margin: 2px !important;
        padding: 2px 6px !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        min-height: 22px !important;
    }
    
    /* Diferentes colores para diferentes tipos de eventos */
    .fc-event[data-type="event"] {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        border-color: #4facfe !important;
    }
    
    .fc-event[data-type="task"] {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%) !important;
        border-color: #43e97b !important;
    }
    
    .fc-event[data-type="payment"] {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        border-color: #f093fb !important;
    }
    
    /* Eventos por defecto */
    .fc-event:not([data-type]) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border-color: #667eea !important;
    }
    
    /* Hover effects para eventos */
    .fc-event:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
        transition: all 0.2s ease !important;
    }
    
    /* Mejorar visibilidad del calendario */
    .fc-daygrid-day {
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
    }
    
    .fc-daygrid-day:hover {
        background: rgba(255, 255, 255, 1) !important;
    }
    
    .fc-day-today {
        background: rgba(102, 126, 234, 0.1) !important;
    }
    
    .fc-daygrid-day-number {
        color: #333 !important;
        font-weight: 600 !important;
        padding: 8px !important;
    }
    
    .fc-col-header-cell {
        background: rgba(255, 255, 255, 0.9) !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        font-weight: 600 !important;
        color: #333 !important;
    }
    
    .fc-scrollgrid {
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        background: white !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }
    
    .fc-toolbar {
        background: var(--glass-bg) !important;
        backdrop-filter: blur(20px) !important;
        border-radius: var(--border-radius) !important;
        padding: 15px !important;
        margin-bottom: 20px !important;
    }
    
    .fc-button-primary {
        background: var(--primary-gradient) !important;
        border: none !important;
        border-radius: 8px !important;
        transition: var(--transition-smooth) !important;
    }
    
    .fc-button-primary:hover {
        transform: translateY(-2px) !important;
        box-shadow: var(--shadow-lg) !important;
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
        width: 120px;
        height: 120px;
        top: 15%;
        left: 8%;
        animation-delay: 0s;
    }

    .floating-circle:nth-child(2) {
        width: 200px;
        height: 200px;
        top: 50%;
        right: 10%;
        animation-delay: 2s;
    }

    .floating-circle:nth-child(3) {
        width: 90px;
        height: 90px;
        bottom: 25%;
        left: 25%;
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

    /* Calendar Specific Styles */
    .calendar-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    /* FullCalendar customizations */
    .fc {
        background: transparent;
    }

    .fc-toolbar-title {
        color: #333 !important;
        font-weight: bold !important;
        font-size: 1.5rem !important;
    }

    .fc-button-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border: none !important;
        border-radius: 10px !important;
        transition: var(--transition-smooth) !important;
    }

    .fc-button-primary:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4) !important;
    }

    .fc-daygrid-day:hover {
        background: rgba(102, 126, 234, 0.1) !important;
    }

    .fc-event {
        border-radius: 8px !important;
        border: none !important;
        padding: 2px 6px !important;
        font-weight: 500 !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
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
<div class="calendar-page hero-section">
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
                        <i class="fas fa-calendar-alt text-white" style="font-size: 3.5rem; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                    </div>
                    <h1 class="text-white fw-bold mb-3 text-glow" style="font-size: 3rem; letter-spacing: 2px;">
                        Calendario Escolar
                    </h1>
                    <p class="text-white-50 fs-5 mb-0" style="max-width: 600px; margin: 0 auto;">
                        Organiza eventos, tareas y pagos de colegiatura en un solo lugar
                    </p>
                </div>

                <!-- Estadísticas del calendario -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="stats-card border-0 rounded-4 text-center py-4 h-100">
                            <div class="card-body p-4 position-relative">
                                <div class="position-relative mb-3">
                                    <i class="fas fa-calendar-check text-white" style="font-size: 3rem; animation: pulse 2s infinite; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                                        <span id="totalEvents">0</span>
                                    </span>
                                </div>
                                <h5 class="fw-bold text-white mb-2 text-shadow">Total Eventos</h5>
                                <small class="text-white" style="opacity: 0.9;">Este mes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card border-0 rounded-4 text-center py-4 h-100">
                            <div class="card-body p-4 position-relative">
                                <div class="position-relative mb-3">
                                    <i class="fas fa-tasks text-white" style="font-size: 3rem; animation: pulse 2s infinite 0.5s; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #ffc107, #ffcd39); color: #212529; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);">
                                        <span id="totalTasks">0</span>
                                    </span>
                                </div>
                                <h5 class="fw-bold text-white mb-2 text-shadow">Tareas</h5>
                                <small class="text-white" style="opacity: 0.9;">Pendientes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card border-0 rounded-4 text-center py-4 h-100">
                            <div class="card-body p-4 position-relative">
                                <div class="position-relative mb-3">
                                    <i class="fas fa-money-bill-wave text-white" style="font-size: 3rem; animation: pulse 2s infinite 1s; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);">
                                        <span id="totalPayments">0</span>
                                    </span>
                                </div>
                                <h5 class="fw-bold text-white mb-2 text-shadow">Pagos</h5>
                                <small class="text-white" style="opacity: 0.9;">Colegiatura</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card border-0 rounded-4 text-center py-4 h-100">
                            <div class="card-body p-4 position-relative">
                                <div class="position-relative mb-3">
                                    <i class="fas fa-exclamation-triangle text-white" style="font-size: 3rem; animation: pulse 2s infinite 1.5s; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background: linear-gradient(135deg, #ffc107, #ff8f00); color: #212529; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);">
                                        <span id="overdueItems">0</span>
                                    </span>
                                </div>
                                <h5 class="fw-bold text-white mb-2 text-shadow">Vencidos</h5>
                                <small class="text-white" style="opacity: 0.9;">Requieren atención</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones principales -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="text-center mb-4">
                    <h3 class="text-white fw-bold mb-2" style="font-size: 1.5rem;">
                        <i class="fas fa-tools me-2"></i>Acciones Rápidas
                    </h3>
                    <p class="text-white-50 mb-0">Gestiona tus actividades académicas</p>
                </div>
                <div class="row g-3 justify-content-center">
                    <div class="col-md-6 col-lg-2">
                        <a href="{{ route('events.create') }}" class="btn btn-success action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                            <div class="btn-content">
                                <i class="fas fa-calendar-plus fs-4 d-block mb-2"></i>
                                <div>Evento</div>
                                <small class="opacity-75">Nuevo evento</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <a href="{{ route('tasks.create') }}" class="btn btn-warning action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                            <div class="btn-content">
                                <i class="fas fa-tasks fs-4 d-block mb-2"></i>
                                <div>Tarea</div>
                                <small class="opacity-75">Nueva tarea</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <a href="{{ route('payments.create') }}" class="btn btn-danger action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                            <div class="btn-content">
                                <i class="fas fa-money-bill-wave fs-4 d-block mb-2"></i>
                                <div>Pago</div>
                                <small class="opacity-75">Registrar pago</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <a href="{{ route('payments.index') }}" class="btn btn-info action-button btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                            <div class="btn-content">
                                <i class="fas fa-receipt fs-4 d-block mb-2"></i>
                                <div>Pagos</div>
                                <small class="opacity-75">Ver pagos</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <button onclick="refreshCalendar()" class="btn action-btn action-button text-white btn-lg w-100 rounded-4 py-3 fw-semibold shadow-lg">
                            <div class="btn-content">
                                <i class="fas fa-sync-alt fs-4 d-block mb-2"></i>
                                <div>Actualizar</div>
                                <small class="opacity-75">Refrescar</small>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controles del calendario -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="filter-card rounded-4 p-4 mb-4 shadow-lg">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <h6 class="fw-semibold text-dark mb-2">
                                <i class="fas fa-eye text-primary me-2"></i>Mostrar en Calendario
                            </h6>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showEvents" checked>
                                    <label class="form-check-label fw-semibold" for="showEvents">
                                        <span class="badge me-2" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">●</span>Eventos
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showTasks" checked>
                                    <label class="form-check-label fw-semibold" for="showTasks">
                                        <span class="badge bg-warning me-2">●</span>Tareas
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="showPayments" checked>
                                    <label class="form-check-label fw-semibold" for="showPayments">
                                        <span class="badge bg-success me-2">●</span>Pagos
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-semibold text-dark mb-2">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>Vista del Calendario
                            </h6>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-view="dayGridMonth" onclick="changeCalendarView('dayGridMonth')">Mes</button>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-view="timeGridWeek" onclick="changeCalendarView('timeGridWeek')">Semana</button>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-view="timeGridDay" onclick="changeCalendarView('timeGridDay')">Día</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-semibold text-dark mb-2">
                                <i class="fas fa-download text-primary me-2"></i>Exportar
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-file-export me-2"></i>Exportar
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel text-success me-2"></i>Excel</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf text-danger me-2"></i>PDF</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-calendar text-info me-2"></i>ICS</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario principal -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="calendar-card p-4 mb-5">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="eventModalTitle">
                    <i class="fas fa-calendar me-2"></i>Detalles del Evento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0" id="eventModalBody">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="editEventBtn">
                    <i class="fas fa-edit me-2"></i>Editar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Cargar rutas de Laravel para JavaScript -->
<script>
    window.routes = {
        calendarEvents: '{{ route("calendar.events") }}',
        paymentsCalendarEvents: '{{ route("payments.calendar-events") }}'
    };
</script>
<!-- Script externo del calendario para evitar conflictos con Vue.js -->
<script src="{{ asset('js/calendar.js') }}"></script>
@endpush

@push('styles')
<style>
/* Encapsular todos los estilos del calendario dentro de .calendar-page */
.calendar-page .gradient-text {
    background: linear-gradient(45deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.calendar-page .header-title {
    color: white;
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.calendar-page .header-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    font-weight: 300;
}

.calendar-page .calendar-toolbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    padding: 1.5rem 0;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.calendar-page .toolbar-content {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    align-items: center;
    justify-content: space-between;
}

.calendar-page .view-switcher .btn-view {
    background: transparent;
    border: 2px solid #e9ecef;
    color: #6c757d;
    padding: 0.5rem 1rem;
    border-radius: 0;
    transition: all 0.3s ease;
    font-weight: 500;
}

.calendar-page .view-switcher .btn-view:first-child {
    border-radius: 25px 0 0 25px;
}

.calendar-page .view-switcher .btn-view:last-child {
    border-radius: 0 25px 25px 0;
}

.calendar-page .view-switcher .btn-view:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.calendar-page .view-switcher .btn-view.active {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-color: #667eea;
    color: white;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.calendar-page .filter-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.calendar-page .filter-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0;
}

.calendar-page .filter-options {
    display: flex;
    gap: 1rem;
}

.calendar-page .color-indicator {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

.calendar-page .color-indicator.tasks {
    background: linear-gradient(45deg, #ffd700, #ffed4e);
}

.calendar-page .color-indicator.events {
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.calendar-page .form-check-label {
    cursor: pointer;
    font-weight: 500;
}

.calendar-page .export-actions {
    display: flex;
    gap: 0.75rem;
    position: relative;
}

/* Botón de exportar moderno */
.calendar-page .btn-export {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.calendar-page .btn-export:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.calendar-page .btn-export:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
}

.calendar-page .btn-export:hover:before {
    left: 100%;
}

.calendar-page .btn-export:active {
    transform: translateY(0);
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
}

.calendar-page .btn-export .dropdown-arrow {
    transition: transform 0.3s ease;
    font-size: 0.8rem;
}

.calendar-page .btn-export[aria-expanded="true"] .dropdown-arrow {
    transform: rotate(180deg);
}

.calendar-page .btn-export.dropdown-open {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

/* Dropdown de exportación */
.calendar-page .export-dropdown {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    padding: 0;
    margin-top: 10px;
    min-width: 280px;
    z-index: 9999 !important;
    position: absolute !important;
}

.calendar-page .export-dropdown .dropdown-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 20px;
    margin: 0;
    border-radius: 15px 15px 0 0;
    font-size: 0.9rem;
    font-weight: 600;
    border-bottom: none;
}

.calendar-page .export-item {
    padding: 0 !important;
    border: none;
    display: flex !important;
    align-items: center;
    text-decoration: none;
    color: #495057;
    transition: all 0.3s ease;
    margin: 5px 10px;
    border-radius: 10px;
}

.calendar-page .export-item:hover {
    background: #f8f9fa;
    color: #495057;
    transform: translateX(5px);
}

.calendar-page .export-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 10px;
    font-size: 1.2rem;
    color: white;
}

.calendar-page .export-icon.excel {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.calendar-page .export-icon.pdf {
    background: linear-gradient(135deg, #dc3545, #fd7e14);
}

.calendar-page .export-icon.ics {
    background: linear-gradient(135deg, #17a2b8, #6f42c1);
}

.calendar-page .export-text {
    flex: 1;
    padding-right: 15px;
}

.calendar-page .export-title {
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 2px;
}

.calendar-page .export-subtitle {
    font-size: 0.8rem;
    color: #6c757d;
}

.calendar-page .calendar-main {
    padding: 2rem 0 4rem;
    position: relative;
    z-index: 1;
}

.calendar-page .calendar-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
}

/* Asegurar que el dropdown esté por encima del calendario */
.calendar-page .dropdown {
    position: relative;
    z-index: 10000;
}

.calendar-page .dropdown-menu.show {
    z-index: 10001 !important;
}

/* Personalización del calendario FullCalendar */
.calendar-page .fc {
    font-family: 'Nunito', sans-serif;
}

.calendar-page .fc-header-toolbar {
    margin-bottom: 1.5rem !important;
    padding: 1rem;
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.calendar-page .fc-button-primary {
    background: linear-gradient(45deg, #667eea, #764ba2) !important;
    border: none !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    padding: 0.5rem 1rem !important;
    transition: all 0.3s ease !important;
}

.calendar-page .fc-button-primary:hover {
    background: linear-gradient(45deg, #5a6fd8, #6a4190) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4) !important;
}

.calendar-page .fc-button-primary:not(:disabled):active,
.calendar-page .fc-button-primary:not(:disabled).fc-button-active {
    background: linear-gradient(45deg, #4c5bc7, #5d3a7d) !important;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.2) !important;
}

.calendar-page .fc-daygrid-day {
    transition: all 0.3s ease;
}

.calendar-page .fc-daygrid-day:hover {
    background: rgba(102, 126, 234, 0.05) !important;
}

.calendar-page .fc-day-today {
    background: linear-gradient(45deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1)) !important;
}

.calendar-page .fc-event {
    border-radius: 8px !important;
    border: none !important;
    padding: 2px 6px !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
    transition: all 0.3s ease !important;
}

.calendar-page .fc-event:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25) !important;
}

.calendar-page .fc-event.task-event {
    background: linear-gradient(45deg, #ffd700, #ffed4e) !important;
    color: #333 !important;
}

.calendar-page .fc-event.event-event {
    background: linear-gradient(45deg, #667eea, #764ba2) !important;
    color: white !important;
}

/* Tooltip mejorado */
.calendar-page .fc-tooltip {
    background: rgba(0, 0, 0, 0.9) !important;
    color: white !important;
    border-radius: 10px !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.9rem !important;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3) !important;
    backdrop-filter: blur(10px) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    z-index: 10001 !important;
}

/* Modales personalizados */
.calendar-page .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.calendar-page .modal-header {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border-radius: 20px 20px 0 0;
    border-bottom: none;
}

.calendar-page .modal-header .btn-close {
    filter: invert(1);
}

.calendar-page .form-control:focus,
.calendar-page .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Loading spinner */
.calendar-page .loading-spinner {
    text-align: center;
    padding: 2rem 0;
}

/* Responsive */
@media (max-width: 768px) {
    .calendar-page .calendar-header {
        padding: 2rem 0 1rem;
    }
    
    .calendar-page .header-title {
        font-size: 2rem;
    }
    
    .calendar-page .toolbar-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .calendar-page .calendar-card {
        padding: 1rem;
        margin: 0 1rem;
    }
    
    .calendar-page .view-switcher {
        width: 100%;
    }
    
    .calendar-page .view-switcher .btn-view {
        flex: 1;
    }
}

@media (max-width: 576px) {
    .calendar-page .filter-options {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .calendar-page .export-actions {
        width: 100%;
        justify-content: center;
    }
    
    .calendar-page .btn-export {
        width: 100%;
        justify-content: center;
        padding: 14px 20px;
    }
    
    .calendar-page .export-dropdown {
        min-width: 95vw;
        left: 50% !important;
        transform: translateX(-50%) !important;
        margin-top: 5px;
    }
    
    .calendar-page .export-item {
        margin: 3px 5px;
    }
    
    .calendar-page .export-icon {
        width: 45px;
        height: 45px;
        margin: 8px;
    }
    
    .calendar-page .export-title {
        font-size: 0.9rem;
    }
    
    .calendar-page .export-subtitle {
        font-size: 0.75rem;
    }
}
</style>
@endpush
