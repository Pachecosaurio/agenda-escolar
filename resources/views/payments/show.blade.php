@extends('layouts.app')

@section('content')
<style>
    /* CSS Variables - Consistente con calendario */
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

    /* Payment Details Card */
    .payment-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        transition: var(--transition-smooth);
    }

    .payment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    }

    .payment-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.9) 100%);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
    }

    .payment-header::before {
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

    .payment-header h2 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        color: white;
    }

    .payment-header p {
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    .payment-content {
        padding: 2.5rem;
    }

    .payment-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-item {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-smooth);
    }

    .info-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .info-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .info-label i {
        margin-right: 0.5rem;
        color: #667eea;
    }

    .info-value {
        font-size: 1.1rem;
        color: #2c3e50;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.8rem;
    }

    .status-pending {
        background: var(--warning-gradient);
        color: white;
    }

    .status-paid {
        background: var(--success-gradient);
        color: white;
    }

    .status-overdue {
        background: var(--secondary-gradient);
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .action-button {
        position: relative;
        overflow: hidden;
        transition: var(--transition-smooth);
        border: none;
        border-radius: 25px;
        padding: 1rem 2rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        min-width: 150px;
        justify-content: center;
    }

    .action-button:hover {
        transform: translateY(-5px) scale(1.05);
        text-decoration: none;
    }

    .btn-edit {
        background: var(--success-gradient);
        color: white;
    }

    .btn-back {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: 2px solid #6c757d;
    }

    .btn-delete {
        background: var(--secondary-gradient);
        color: white;
    }
</style>

<div class="hero-section">
    <!-- Floating Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="hero-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="payment-card">
                        <div class="payment-header">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-receipt" style="font-size: 3rem;"></i>
                            </div>
                            <h2>{{ $payment->title }}</h2>
                            <p class="mb-0" style="opacity: 0.9;">Detalles del Pago</p>
                        </div>

                        <div class="payment-content">
                            <div class="payment-info">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-dollar-sign"></i> Monto
                                    </div>
                                    <div class="info-value">
                                        ${{ number_format($payment->amount, 2) }}
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-flag"></i> Estado
                                    </div>
                                    <div class="info-value">
                                        <span class="status-badge status-{{ $payment->status }}">
                                            {{ $payment->status_text }}
                                        </span>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-tags"></i> Categoría
                                    </div>
                                    <div class="info-value">
                                        {{ $payment->category_text }}
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-calendar-alt"></i> Fecha Límite
                                    </div>
                                    <div class="info-value">
                                        {{ $payment->due_date->format('d/m/Y') }}
                                    </div>
                                </div>

                                @if($payment->paid_date)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-calendar-check"></i> Fecha de Pago
                                    </div>
                                    <div class="info-value">
                                        {{ $payment->paid_date->format('d/m/Y') }}
                                    </div>
                                </div>
                                @endif

                                @if($payment->payment_method)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-credit-card"></i> Método de Pago
                                    </div>
                                    <div class="info-value">
                                        {{ $payment->payment_method }}
                                    </div>
                                </div>
                                @endif

                                @if($payment->reference)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-hashtag"></i> Referencia
                                    </div>
                                    <div class="info-value">
                                        {{ $payment->reference }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if($payment->description)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-align-left"></i> Descripción
                                </div>
                                <div class="info-value">
                                    {{ $payment->description }}
                                </div>
                            </div>
                            @endif

                            @if($payment->notes)
                            <div class="info-item mt-3">
                                <div class="info-label">
                                    <i class="fas fa-sticky-note"></i> Notas
                                </div>
                                <div class="info-value">
                                    {{ $payment->notes }}
                                </div>
                            </div>
                            @endif

                            <div class="action-buttons mt-4">
                                <a href="{{ route('payments.index') }}" class="action-button btn-back">
                                    <i class="fas fa-arrow-left me-2"></i> Volver
                                </a>
                                <a href="{{ route('payments.edit', $payment) }}" class="action-button btn-edit">
                                    <i class="fas fa-edit me-2"></i> Editar
                                </a>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('¿Estás seguro de eliminar este pago?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button btn-delete">
                                        <i class="fas fa-trash me-2"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection