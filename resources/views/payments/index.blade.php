@extends('layouts.app')

@section('content')

<div class="payments-hero-section">
    <!-- Floating Elements -->
    <div class="payments-floating-elements">
        <div class="payments-floating-circle"></div>
        <div class="payments-floating-circle"></div>
        <div class="payments-floating-circle"></div>
    </div>

    <div class="payments-hero-content">
        <div class="container-fluid">
            <!-- Header Moderno con Glass Effect -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="payments-stats-card p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h1 class="mb-0 text-glow" style="font-size: 2.5rem; font-weight: 700; color: white;">
                                    <div class="icon-wrapper d-inline-block me-3">
                                        <i class="fas fa-wallet" style="font-size: 2rem;"></i>
                                    </div>
                                    Mi Centro de Pagos
                                </h1>
                                <p class="mb-0 mt-2 text-shadow" style="font-size: 1.1rem; opacity: 0.9; color: white;">
                                    Gestiona tus pagos de manera inteligente y eficiente
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('payments.create') }}" class="payments-action-button btn-primary-modern hovering">
                                    <i class="fas fa-plus me-2"></i>
                                    Nuevo Pago
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="payments-stats-card p-3 text-center text-white">
                        <div class="icon-wrapper">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                        </div>
                        <h4 class="text-glow">${{ number_format($stats['totalAmount'], 2) }}</h4>
                        <p class="mb-0 text-shadow">Total a Pagar</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="payments-stats-card p-3 text-center text-white">
                        <div class="icon-wrapper">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                        </div>
                        <h4 class="text-glow">${{ number_format($stats['paidAmount'], 2) }}</h4>
                        <p class="mb-0 text-shadow">Total Pagado</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="payments-stats-card p-3 text-center text-white">
                        <div class="icon-wrapper">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                        </div>
                        <h4 class="text-glow">${{ number_format($stats['pendingAmount'], 2) }}</h4>
                        <p class="mb-0 text-shadow">Total Pendiente</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="payments-stats-card p-3 text-center text-white">
                        <div class="icon-wrapper">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                        </div>
                        <h4 class="text-glow">{{ $stats['totalPayments'] }}</h4>
                        <p class="mb-0 text-shadow">Total Registros</p>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="payments-filter-card p-4">
                        <h5 class="mb-3">
                            <i class="fas fa-filter me-2"></i>
                            Filtros de Búsqueda
                        </h5>
                        <form method="GET" action="{{ route('payments.index') }}">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select id="status" class="form-select" name="status">
                                        <option value="">Todos los estados</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Vencido</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="category" class="form-label">Categoría</label>
                                    <select id="category" class="form-select" name="category">
                                        <option value="">Todas las categorías</option>
                                        <option value="tuition" {{ request('category') == 'tuition' ? 'selected' : '' }}>Colegiatura</option>
                                        <option value="books" {{ request('category') == 'books' ? 'selected' : '' }}>Libros</option>
                                        <option value="activities" {{ request('category') == 'activities' ? 'selected' : '' }}>Actividades</option>
                                        <option value="transport" {{ request('category') == 'transport' ? 'selected' : '' }}>Transporte</option>
                                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Otros</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="search" class="form-label">Búsqueda</label>
                                    <input type="text" id="search" class="form-control" name="search" value="{{ request('search') }}" 
                                           placeholder="Buscar por descripción...">
                                </div>
                                <div class="col-md-2 mb-3 d-flex align-items-end">
                                    <div class="d-grid w-100">
                                        <button type="submit" class="payments-action-button btn-warning-modern">
                                            <i class="fas fa-search"></i> Filtrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                    <i class="fas fa-plus-circle"></i> Nuevo Pago
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row fade-in" style="animation-delay: 0.2s;">
        <div class="col-md-3">
            <div class="payments-stat-card text-center">
                <i class="fas fa-receipt fa-2x mb-2"></i>
                <h3>{{ $stats['totalPayments'] }}</h3>
                <p>Total Pagos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="payments-stat-card text-center">
                <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                <h3>${{ number_format($stats['totalAmount'], 2) }}</h3>
                <p>Monto Total</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="payments-stat-card text-center">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <h3>${{ number_format($stats['paidAmount'], 2) }}</h3>
                <p>Pagado</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="payments-stat-card text-center">
                <i class="fas fa-clock fa-2x mb-2"></i>
                <h3>${{ number_format($stats['pendingAmount'], 2) }}</h3>
                <p>Pendiente</p>
            </div>
        </div>
    </div>

    <!-- Lista de Pagos Moderna -->
    <div class="payments-payment-card fade-in" style="animation-delay: 0.4s;">
        <div class="payments-payment-header">
            <h4 class="mb-0">
                <i class="fas fa-list-alt"></i> Mis Registros de Pagos
            </h4>
        </div>

        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success m-3 fade-in" style="border-radius: 10px; border: none; box-shadow: 0 4px 15px rgba(40,167,69,0.2);">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($payments->count() > 0)
                <div class="p-3">
                    @foreach($payments as $index => $payment)
                        <div class="payments-payment-item {{ $payment->status }} fade-in" style="animation-delay: {{ $index * 0.1 }}s;">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="payment-icon me-3">
                                            @if($payment->category == 'colegiatura')
                                                <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                                            @elseif($payment->category == 'libros')
                                                <i class="fas fa-book fa-2x text-info"></i>
                                            @elseif($payment->category == 'uniformes')
                                                <i class="fas fa-tshirt fa-2x text-warning"></i>
                                            @elseif($payment->category == 'transporte')
                                                <i class="fas fa-bus fa-2x text-success"></i>
                                            @else
                                                <i class="fas fa-receipt fa-2x text-secondary"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $payment->title }}</h5>
                                            @if($payment->description)
                                                <p class="text-muted mb-0 small">{{ Str::limit($payment->description, 50) }}</p>
                                            @endif
                                            <span class="badge rounded-pill" style="background: linear-gradient(45deg, #667eea, #764ba2); color: white;">
                                                {{ $payment->category_text }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 text-center">
                                    <div class="amount-display">
                                        ${{ number_format($payment->amount, 2) }}
                                    </div>
                                </div>

                                <div class="col-md-2 text-center">
                                    <div class="date-info">
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                        <div>{{ $payment->due_date->format('d/m/Y') }}</div>
                                        @if($payment->due_date->isToday())
                                            <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Hoy</small>
                                        @elseif($payment->due_date->isPast())
                                            <small class="text-danger"><i class="fas fa-clock"></i> Vencido</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-2 text-center">
                                    <span class="badge badge-status bg-{{ $payment->status_color }} p-2" style="border-radius: 20px;">
                                        @if($payment->status == 'paid')
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($payment->status == 'pending')
                                            <i class="fas fa-clock"></i>
                                        @else
                                            <i class="fas fa-exclamation-triangle"></i>
                                        @endif
                                        {{ $payment->status_text }}
                                    </span>
                                    @if($payment->paid_date)
                                        <div><small class="text-muted">{{ $payment->paid_date->format('d/m/Y') }}</small></div>
                                    @endif
                                </div>

                                <div class="col-md-2 text-end">
                                    <div class="btn-group-vertical" role="group">
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm mb-1" style="background: linear-gradient(45deg, #11998e, #38ef7d); color: white; border-radius: 20px;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm mb-1" style="background: linear-gradient(45deg, #667eea, #764ba2); color: white; border-radius: 20px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background: linear-gradient(45deg, #fc466b, #3f5efb); color: white; border-radius: 20px;" 
                                                    onclick="return confirm('¿Estás seguro de eliminar este pago?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center p-3">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="floating mb-4">
                        <i class="fas fa-receipt fa-5x text-muted" style="opacity: 0.3;"></i>
                    </div>
                    <h3 class="text-muted mb-3">¡Tu espacio está esperando!</h3>
                    <p class="text-muted mb-4">Comienza organizando tus pagos de manera inteligente</p>
                    <a href="{{ route('payments.create') }}" class="btn btn-modern btn-primary-modern btn-lg pulse">
                        <i class="fas fa-rocket"></i> Crear Mi Primer Pago
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Animaciones adicionales con JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Efecto hover en las tarjetas de pago
    const paymentItems = document.querySelectorAll('.payment-item');
    paymentItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(10px) scale(1.02)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0) scale(1)';
        });
    });

    // Efecto de conteo animado para las estadísticas
    const statNumbers = document.querySelectorAll('.stat-card h3');
    statNumbers.forEach(stat => {
        const finalValue = stat.textContent.replace(/[^0-9.]/g, '');
        if (finalValue && !isNaN(finalValue)) {
            animateNumber(stat, 0, parseFloat(finalValue), 1500);
        }
    });
});

function animateNumber(element, start, end, duration) {
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= end) {
            current = end;
            clearInterval(timer);
        }
        
        if (element.textContent.includes('$')) {
            element.textContent = '$' + current.toFixed(2);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}
</script>
@endsection