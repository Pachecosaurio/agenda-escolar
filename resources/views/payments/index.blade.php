{{-- Vista de Pagos: hero con estadísticas, filtros, categorías y tabla principal. Tema morado con glass y accesibilidad mejorada. --}}
@extends('layouts.app')

@push('styles')
<style>
    /* Tema morado */
    /* Hero únicamente para cabecera y estadísticas */
    .payments-page .payments-hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #a855f7 100%); position:relative; overflow:hidden; padding-bottom:3rem; }
    .payments-page .payments-hero .floating-circle { position:absolute; border-radius:50%; background:rgba(255,255,255,0.12); animation: floatP 18s linear infinite; }
    .payments-page .payments-hero .floating-circle:nth-child(1){ width:110px; height:110px; left:8%; animation-delay:0s; }
    .payments-page .payments-hero .floating-circle:nth-child(2){ width:160px; height:160px; left:78%; animation-delay:4s; }
    .payments-page .payments-hero .floating-circle:nth-child(3){ width:80px; height:80px; left:48%; animation-delay:8s; }
    @keyframes floatP { 0%{ transform:translateY(100vh) rotate(0deg); opacity:0;} 10%{opacity:1;} 90%{opacity:1;} 100%{ transform:translateY(-120px) rotate(360deg); opacity:0;} }
    .payments-page .filter-card { background:rgba(255,255,255,0.95); backdrop-filter:blur(25px); border:2px solid rgba(255,255,255,0.5); border-radius:25px; position:relative; overflow:hidden; }
    .payments-page .filter-card:before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,#667eea,#764ba2,#a855f7,#667eea); background-size:300% 100%; animation:gradientShift 6s ease infinite; }
    @keyframes gradientShift {0%,100%{background-position:0% 50%;}50%{background-position:100% 50%;}}
    .payments-page .payment-stats-card { background:linear-gradient(135deg,rgba(255,255,255,0.15),rgba(255,255,255,0.05)); backdrop-filter:blur(22px); border:1px solid rgba(255,255,255,0.25); border-radius:22px; position:relative; overflow:hidden; transition:.5s cubic-bezier(.25,.8,.25,1); }
    .payments-page .payment-stats-card:hover { transform:translateY(-10px) scale(1.02); box-shadow:0 18px 40px rgba(0,0,0,0.25); border-color:rgba(255,255,255,0.45); }
    .payments-page .payment-stats-card:after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#667eea,#764ba2,#a855f7); }
    .payments-page .quick-badge { background:rgba(255,255,255,0.2); border:1px solid rgba(255,255,255,0.35); padding:.55rem 1rem; border-radius:25px; font-size:.8rem; letter-spacing:.5px; }
    .payments-page .category-chip { background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.3); padding:.35rem .75rem; border-radius:20px; font-size:.75rem; letter-spacing:.5px; display:inline-flex; align-items:center; gap:.35rem; }
    .payments-page .payments-table-wrapper { border-radius:0 0 22px 22px; }
    .payments-page .table.glass-table { --bs-table-bg:transparent; color:#fff; }
    .payments-page .table.glass-table tbody tr { transition:.35s; }
    .payments-page .table.glass-table tbody tr:hover { background:rgba(255,255,255,0.06); }
    .payments-page .action-dropdown-toggle { width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:50%; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); color:#fff; }
    .payments-page .action-dropdown-toggle:hover { background:rgba(255,255,255,0.25); }
    .payments-page .category-card { background:rgba(255,255,255,0.15); backdrop-filter:blur(15px); border:1px solid rgba(255,255,255,0.35); border-radius:18px; transition:.45s cubic-bezier(.25,.8,.25,1); position:relative; overflow:hidden; }
    .payments-page .category-card:hover { transform:translateY(-6px); box-shadow:0 15px 35px rgba(0,0,0,0.25); }
    .payments-page .category-card:before { content:''; position:absolute; top:0; left:-100%; width:100%; height:100%; background:linear-gradient(120deg,transparent,rgba(255,255,255,0.25),transparent); transition:.7s; }
    .payments-page .category-card:hover:before { left:100%; }
    .payments-page .empty-state-icon { width:140px; height:140px; border-radius:50%; background:rgba(255,255,255,0.12); backdrop-filter:blur(10px); display:flex; align-items:center; justify-content:center; border:2px solid rgba(255,255,255,0.35); }
    .payments-page .fade-seq { opacity:0; transform:translateY(30px); }
    .payments-page .progress-mini { height:6px; background:rgba(255,255,255,0.2); border-radius:10px; overflow:hidden; }
    .payments-page .progress-mini > span { display:block; height:100%; background:linear-gradient(90deg,#667eea,#a855f7); }
    @media (max-width: 768px){ .payments-hero h1{font-size:2.4rem !important;} .payment-stats-card{margin-bottom:1rem;} }
    /* Botones de acción (antes sin estilo, texto blanco invisible sobre fondos claros) */
    .payments-page .action-btn { background:linear-gradient(90deg,#667eea,#764ba2,#a855f7); border:none; box-shadow:0 6px 18px rgba(0,0,0,0.25); font-weight:600; letter-spacing:.5px; }
    .payments-page .action-btn:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(0,0,0,0.35); color:#fff; }
    .payments-page .action-btn:active { transform:translateY(-1px) scale(.97); }
    .payments-page .glass-effect { background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.35); backdrop-filter:blur(6px); }
    .payments-page .glass-effect:hover { background:rgba(255,255,255,0.25); color:#fff; }
    @media (prefers-reduced-motion: reduce) { .payments-page .action-btn:hover { transform:none; box-shadow:0 6px 18px rgba(0,0,0,0.25);} }
    /* Contenido posterior (fondo claro) */
    /* Main content blanco independiente del hero */
    .payments-content { background:#ffffff; position:relative; z-index:5; }
    .payments-content-section { padding-top:2rem; padding-bottom:3rem; }
    .payments-content h3, .payments-content h4, .payments-content h6 { color:#2d2f35; }
    .payments-content .text-section-muted { color:#6c757d; }
    .payments-content .category-card { background:#f8f9fc; border:1px solid #e2e8f0; }
    .payments-content .category-card h6, .payments-content .category-card h4 { color:#2d2f35; }
    .payments-content .category-card .category-chip { background:#eef2ff; border:1px solid #c7d2fe; color:#4338ca; }
    .payments-content .category-card i { color:#4f46e5; }
    .payments-content .table.glass-table { color:#2d2f35; }
    .payments-content .table.glass-table thead tr { color:#4b5563; }
    .payments-content .table.glass-table tbody tr { color:#2d2f35; }
    .payments-content .category-chip i { color:inherit; }
    .payments-content .empty-state-icon i { color:#4f46e5 !important; opacity:.9 !important; }
    .payments-content .empty-state-icon { background:#f1f5f9; border-color:#e2e8f0; }
    .payments-content .action-dropdown-toggle { background:#eef2ff; border-color:#c7d2fe; color:#4f46e5; }
    .payments-content .action-dropdown-toggle:hover { background:#e0e7ff; }
    body { background:#f4f6fb; }
    .page-wrapper-bg { background:linear-gradient(180deg,#f4f6fb 0%,#eef2f9 100%); }
    .payments-page .payments-hero { border-bottom:6px solid rgba(255,255,255,0.3); }
</style>
@endpush

@section('content')
<div class="page-wrapper-bg">
<div class="payments-page">
<div class="payments-hero">
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>
    <div class="container-fluid hero-content py-5">
        <!-- HERO -->
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center mb-5 fade-seq" data-ord="0">
                <div class="mx-auto mb-4" style="width:130px;height:130px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.18);border:3px solid rgba(255,255,255,0.4);backdrop-filter:blur(10px);">
                    <i class="fas fa-wallet text-white" style="font-size:3.6rem;"></i>
                </div>
                <h1 class="text-white fw-bold mb-3" style="font-size:3.2rem;letter-spacing:-1px;text-shadow:0 4px 18px rgba(0,0,0,0.35);">Gestión de Pagos</h1>
                <p class="text-white-50 mb-3 fs-5" style="max-width:620px;margin:0 auto;">Administra tus obligaciones económicas escolares con estadísticas, filtros y visualización clara del estado de cada pago.</p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <span class="quick-badge"><i class="fas fa-chart-pie me-1"></i>Resumen</span>
                    <span class="quick-badge"><i class="fas fa-filter me-1"></i>Filtros</span>
                    <span class="quick-badge"><i class="fas fa-tags me-1"></i>Categorías</span>
                    <span class="quick-badge"><i class="fas fa-list me-1"></i>Listado</span>
                </div>
            </div>
        </div>

        <!-- STATS -->
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3 fade-seq" data-ord="1">
                <div class="payment-stats-card h-100 p-4 text-center">
                    <div class="mb-3"><i class="fas fa-coins text-white" style="font-size:3rem;"></i></div>
                    <h3 class="text-white mb-1" style="font-size:1.9rem;">${{ number_format($stats['totalAmount'],2) }}</h3>
                    <p class="text-white-50 mb-2">Monto Total</p>
                    <div class="progress-mini"><span style="width:100%"></span></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-seq" data-ord="2">
                <div class="payment-stats-card h-100 p-4 text-center">
                    <div class="mb-3"><i class="fas fa-check-circle text-white" style="font-size:3rem;"></i></div>
                    <h3 class="text-white mb-1" style="font-size:1.9rem;">${{ number_format($stats['paidAmount'],2) }}</h3>
                    <p class="text-white-50 mb-2">Pagado ({{ $stats['paidCount'] }})</p>
                    @php $paidPerc = $stats['totalAmount']>0? ($stats['paidAmount']/$stats['totalAmount']*100):0; @endphp
                    <div class="progress-mini"><span data-percentage="{{ round($paidPerc,2) }}"></span></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-seq" data-ord="3">
                <div class="payment-stats-card h-100 p-4 text-center">
                    <div class="mb-3"><i class="fas fa-hourglass-half text-white" style="font-size:3rem;"></i></div>
                    <h3 class="text-white mb-1" style="font-size:1.9rem;">${{ number_format($stats['pendingAmount'],2) }}</h3>
                    <p class="text-white-50 mb-2">Pendiente ({{ $stats['pendingCount'] }})</p>
                    @php $pendingPerc = $stats['totalAmount']>0? ($stats['pendingAmount']/$stats['totalAmount']*100):0; @endphp
                    <div class="progress-mini"><span data-percentage="{{ round($pendingPerc,2) }}"></span></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 fade-seq" data-ord="4">
                <div class="payment-stats-card h-100 p-4 text-center">
                    <div class="mb-3"><i class="fas fa-exclamation-triangle text-white" style="font-size:3rem;"></i></div>
                    <h3 class="text-white mb-1" style="font-size:1.9rem;">${{ number_format($stats['overdueAmount'],2) }}</h3>
                    <p class="text-white-50 mb-2">Vencido ({{ $stats['overdueCount'] }})</p>
                    @php $overPerc = $stats['totalAmount']>0? ($stats['overdueAmount']/$stats['totalAmount']*100):0; @endphp
                    <div class="progress-mini"><span data-percentage="{{ round($overPerc,2) }}"></span></div>
                </div>
            </div>
        </div>

        <!-- FIN HERO (hero + stats) -->
    </div><!-- /payments-hero -->
</div><!-- /payments-page (hero section end) -->

<!-- CONTENIDO PRINCIPAL -->
<div class="payments-content">
      <div class="container-fluid payments-content-section">
        <!-- FILTROS -->
        <div class="row mb-5 fade-seq" data-ord="5">
            <div class="col-lg-10 mx-auto">
                <div class="filter-card p-4 shadow-lg">
                    <form method="GET" action="{{ route('payments.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="filter-q" class="form-label fw-semibold text-dark"><i class="fas fa-search me-1 text-success" aria-hidden="true"></i>Buscar</label>
                            <input id="filter-q" type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Título o nota" style="border-radius:15px;background:rgba(248,249,250,0.85);">
                        </div>
                        <div class="col-md-2">
                            <label for="filter-status" class="form-label fw-semibold text-dark"><i class="fas fa-info-circle me-1 text-success" aria-hidden="true"></i>Estado</label>
                            <select id="filter-status" name="status" class="form-select" style="border-radius:15px;background:rgba(248,249,250,0.85);">
                                <option value="">Todos</option>
                                <option value="pending" @selected($filters['status']==='pending')>Pendiente</option>
                                <option value="paid" @selected($filters['status']==='paid')>Pagado</option>
                                <option value="overdue" @selected($filters['status']==='overdue')>Vencido</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter-category" class="form-label fw-semibold text-dark"><i class="fas fa-tags me-1 text-success" aria-hidden="true"></i>Categoría</label>
                            <select id="filter-category" name="category" class="form-select" style="border-radius:15px;background:rgba(248,249,250,0.85);">
                                <option value="">Todas</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" @selected($filters['category']===$key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter-from" class="form-label fw-semibold text-dark"><i class="fas fa-calendar me-1 text-success" aria-hidden="true"></i>Desde</label>
                            <input id="filter-from" type="date" name="from" value="{{ $filters['from'] }}" class="form-control" style="border-radius:15px;background:rgba(248,249,250,0.85);">
                        </div>
                        <div class="col-md-2">
                            <label for="filter-to" class="form-label fw-semibold text-dark"><i class="fas fa-calendar-alt me-1 text-success" aria-hidden="true"></i>Hasta</label>
                            <input id="filter-to" type="date" name="to" value="{{ $filters['to'] }}" class="form-control" style="border-radius:15px;background:rgba(248,249,250,0.85);">
                        </div>
                        <div class="col-md-1 d-grid">
                            <button class="btn action-btn text-white rounded-pill" aria-label="Aplicar filtros de pagos"><i class="fas fa-filter me-1" aria-hidden="true"></i>Filtrar</button>
                        </div>
                        <div class="col-12 d-flex flex-wrap gap-2 mt-1">
                            @if($filters['q'] || $filters['status'] || $filters['category'] || $filters['from'] || $filters['to'])
                                <a href="{{ route('payments.index') }}" class="btn btn-light rounded-pill px-3"><i class="fas fa-times me-1"></i>Limpiar</a>
                            @endif
                            <a href="{{ route('payments.create') }}" class="btn action-btn text-white rounded-pill px-3"><i class="fas fa-plus me-1"></i>Nuevo</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- CATEGORÍAS -->
        <div class="row mb-5 fade-seq" data-ord="6">
            <div class="col-lg-10 mx-auto">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold mb-0"><i class="fas fa-tags me-2 text-primary"></i>Categorías</h3>
                    <span class="text-section-muted small">Distribución y montos</span>
                </div>
                <div class="row g-4">
                    @php $totalCatAmount = array_sum(array_map(fn($c)=>$c['amount'],$categoryDistribution)); @endphp
                    @foreach($categoryDistribution as $key => $info)
                        @php $portion = $totalCatAmount>0? ($info['amount']/$totalCatAmount*100):0; @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="category-card p-4 h-100">
                                <div class="d-flex align-items-center mb-3">
                                    <div style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#eef2ff;border:2px solid #c7d2fe;">
                                        @switch($key)
                                            @case('tuition')<i class="fas fa-graduation-cap"></i>@break
                                            @case('books')<i class="fas fa-book"></i>@break
                                            @case('activities')<i class="fas fa-running"></i>@break
                                            @case('transport')<i class="fas fa-bus"></i>@break
                                            @case('cafeteria')<i class="fas fa-utensils"></i>@break
                                            @default <i class="fas fa-receipt"></i>
                                        @endswitch
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">{{ $info['label'] }}</h6>
                                        <span class="category-chip"><i class="fas fa-layer-group"></i>{{ $info['count'] }}</span>
                                    </div>
                                </div>
                                <h4 class="mb-1" style="font-size:1.4rem;">${{ number_format($info['amount'],2) }}</h4>
                                <p class="text-section-muted mb-2 small">Participación: {{ round($portion,1) }}%</p>
                                <div class="progress-mini mb-1"><span data-percentage="{{ round($portion,2) }}"></span></div>
                            </div>
                        </div>
                    @endforeach
                    @if(empty($categoryDistribution))
                        <div class="col-12 text-center text-section-muted">Sin datos todavía.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- TABLA -->
        <div class="row fade-seq mb-5" data-ord="7">
            <div class="col-lg-10 mx-auto">
                <div class="glass-card payments-table-wrapper">
                    <div class="p-4" style="background:linear-gradient(135deg,#eef2ff,#dbeafe,#c7d2fe);border-radius:22px 22px 0 0;">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-list fa-2x text-primary me-3"></i>
                                <div>
                                    <h3 class="mb-0">Listado de Pagos</h3>
                                    <p class="text-section-muted mb-0 small">Resultados {{ $payments->total() }} @if($filters['q']||$filters['status']||$filters['category']||$filters['from']||$filters['to']) filtrados @endif</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('payments.create') }}" class="btn action-btn text-white rounded-pill"><i class="fas fa-plus me-1"></i>Nuevo</a>
                                @if($filters['q']||$filters['status']||$filters['category']||$filters['from']||$filters['to'])
                                    <a href="{{ route('payments.index') }}" class="btn btn-light rounded-pill"><i class="fas fa-undo"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-0">
                        @if($payments->count())
                            <div class="table-responsive">
                                <table class="table glass-table align-middle mb-0">
                                    <thead>
                                        <tr class="text-section-muted">
                                            <th>Título</th>
                                            <th>Monto</th>
                                            <th>Vence</th>
                                            <th>Estado</th>
                                            <th>Categoría</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                            <tr class="payment-row-{{ $payment->status }}">
                                                <td style="max-width:220px;">
                                                    <a href="{{ route('payments.show',$payment) }}" class="text-decoration-none fw-semibold">{{ $payment->title }}</a>
                                                    @if($payment->notes)
                                                        <div class="text-section-muted small">{{ Str::limit($payment->notes,50) }}</div>
                                                    @endif
                                                </td>
                                                <td>${{ number_format($payment->amount,2) }}</td>
                                                <td>{{ $payment->due_date?->format('d/m/Y') }}</td>
                                                <td><x-payment.status-badge :status="$payment->status" /></td>
                                                <td><span class="category-chip"><i class="fas fa-tag"></i>{{ $payment->category_text }}</span></td>
                                                <td class="text-end">
                                                    <div class="dropdown">
                                                        <button class="action-dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v small"></i></button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                            <li><a class="dropdown-item" href="{{ route('payments.show',$payment) }}"><i class="fas fa-eye text-primary me-2"></i>Ver</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('payments.edit',$payment) }}"><i class="fas fa-edit text-success me-2"></i>Editar</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('payments.destroy',$payment) }}" method="POST" onsubmit="return confirm('¿Eliminar pago?');">
                                                                    @csrf @method('DELETE')
                                                                    <button class="dropdown-item text-danger"><i class="fas fa-trash me-2"></i>Eliminar</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-4">
                                {{ $payments->links() }}
                            </div>
                        @else
                            <div class="p-5 text-center">
                                <div class="empty-state-icon mx-auto mb-4">
                                    <i class="fas fa-inbox" style="font-size:3rem;"></i>
                                </div>
                                <h4 class="fw-bold mb-2">Sin pagos</h4>
                                <p class="text-section-muted mb-4">No hay registros que coincidan con los filtros actuales.</p>
                                <a href="{{ route('payments.create') }}" class="btn action-btn text-white rounded-pill px-4"><i class="fas fa-plus me-1"></i>Crear tu primer pago</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
            </div><!-- /container-fluid payments-content-section -->
        </div><!-- /payments-page payments-content -->

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Animación secuencial
        const seq = document.querySelectorAll('.fade-seq');
        seq.forEach((el,i)=>{
            const ord = parseInt(el.getAttribute('data-ord')) || i;
            setTimeout(()=>{
                el.style.transition='all .8s cubic-bezier(.25,.8,.25,1)';
                el.style.opacity=1; el.style.transform='translateY(0)';
            }, ord*120);
        });

        // Aplicar porcentajes a barras de progreso
        document.querySelectorAll('.progress-mini span[data-percentage]').forEach(bar => {
            const pct = parseFloat(bar.getAttribute('data-percentage')) || 0;
            requestAnimationFrame(()=> { bar.style.width = pct + '%'; });
        });
    });
</script>
@endpush
@endsection