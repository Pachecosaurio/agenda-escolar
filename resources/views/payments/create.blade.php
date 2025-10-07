@extends('layouts.app')

@push('styles')
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

    /* Form Card */
    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        transition: var(--transition-smooth);
    }

    .form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    }

    /* Form Header con glass effect */
    .form-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.9) 100%);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
    }

    .form-header::before {
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

    .form-header h2 {
        position: relative;
        z-index: 1;
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        color: white;
    }

    /* Icon Wrapper - Igual a eventos */
    .icon-wrapper {
        animation: float 6s ease-in-out infinite;
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
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

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Form Content */
    .floating-form {
        padding: 2.5rem;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Groups */
    .form-group-modern {
        margin-bottom: 2rem;
        position: relative;
    }

    .form-control-modern {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        padding: 1rem 1.2rem;
        font-size: 1rem;
        transition: var(--transition-smooth);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .form-control-modern:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
        transform: translateY(-2px);
        outline: none;
    }

    .form-label-modern {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .form-label-modern i {
        margin-right: 0.5rem;
        color: #667eea;
    }

    /* Category Visual Selectors */
    .category-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .category-option {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
    }

    .category-option:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: var(--shadow-lg);
        border-color: #667eea;
    }

    .category-option.selected {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        transform: translateY(-5px) scale(1.05);
        box-shadow: var(--shadow-xl);
    }

    .category-option i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .category-option input[type="radio"] {
        display: none;
    }

    /* Action Buttons */
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
        width: 100%;
    }

    .action-button:hover {
        transform: translateY(-5px) scale(1.05);
    }

    .btn-create {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-cancel {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: 2px solid #6c757d;
    }

    /* Alert Styles */
    .alert {
        font-size: 0.9rem;
        border: none;
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        margin-bottom: 2rem;
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

    /* Ripple Effect */
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .btn-cancel {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        border-radius: 25px;
        padding: 1rem 2rem;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        transform: translateY(-2px);
        color: white;
    }

    .input-group-modern .input-group-text {
        background: #667eea;
        color: white;
        border: none;
        border-radius: 15px 0 0 15px;
        font-weight: bold;
    }

    .category-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .category-option {
        position: relative;
    }

    .category-option input[type="radio"] {
        display: none;
    }

    .category-label {
        display: block;
        padding: 1rem;
        background: white;
        border: 2px solid #e3e6f0;
        border-radius: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-label:hover {
        border-color: #667eea;
        transform: translateY(-2px);
    }

    .category-option input[type="radio"]:checked + .category-label {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #667eea;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .progress-indicator {
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
        margin-bottom: 2rem;
        animation: progressLoad 1s ease-out;
    }

    @keyframes progressLoad {
        from { width: 0%; }
        to { width: 100%; }
    }
</style>
@endpush

@section('content')
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
                    <div class="form-card">
                        <div class="form-header">
                            <div class="icon-wrapper mb-3">
                                <i class="fas fa-plus-circle" style="font-size: 3rem;"></i>
                            </div>
                            <h2>Crear Nuevo Pago</h2>
                            <p class="mb-0" style="opacity: 0.9;">Registra un nuevo pago de manera rápida y sencilla</p>
                        </div>

                        <div class="floating-form">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Por favor corrige los errores en el formulario
                                </div>
                            @endif

                            <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
                                @csrf
                                
                                <!-- Título -->
                                <div class="form-group-modern">
                                    <label for="title" class="form-label-modern">
                                        <i class="fas fa-heading"></i> Título del Pago *
                                    </label>
                                    <input type="text" class="form-control-modern @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required
                                           placeholder="Ej: Colegiatura Septiembre 2025">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Descripción -->
                                <div class="form-group-modern">
                                    <label for="description" class="form-label-modern">
                                        <i class="fas fa-align-left"></i> Descripción
                                    </label>
                                    <textarea class="form-control-modern @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3"
                                              placeholder="Describe los detalles del pago...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <!-- Monto -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="amount" class="form-label-modern">
                                                <i class="fas fa-dollar-sign"></i> Monto *
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text" style="background: rgba(255,255,255,0.9); border: 2px solid rgba(255,255,255,0.3); border-right: none; border-radius: 15px 0 0 15px;">$</span>
                                                <input type="number" step="0.01" class="form-control-modern @error('amount') is-invalid @enderror" 
                                                       id="amount" name="amount" value="{{ old('amount') }}" required
                                                       placeholder="0.00" style="border-left: none; border-radius: 0 15px 15px 0;">
                                            </div>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Fecha Límite -->
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="due_date" class="form-label-modern">
                                                <i class="fas fa-calendar-alt"></i> Fecha Límite *
                                            </label>
                                            <input type="date" class="form-control-modern @error('due_date') is-invalid @enderror" 
                                                   id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                                            @error('due_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Categoría con selector visual mejorado -->
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fas fa-tags"></i> Categoría *
                                    </label>
                                    <div class="category-selector">
                                        <div class="category-option" data-category="tuition">
                                            <input type="radio" id="category_tuition" name="category" value="tuition" 
                                                   {{ old('category') == 'tuition' ? 'checked' : '' }} required>
                                            <label for="category_tuition">
                                                <i class="fas fa-graduation-cap"></i>
                                                <span>Colegiatura</span>
                                            </label>
                                        </div>
                                        <div class="category-option" data-category="books">
                                            <input type="radio" id="category_books" name="category" value="books" 
                                                   {{ old('category') == 'books' ? 'checked' : '' }} required>
                                            <label for="category_books">
                                                <i class="fas fa-book"></i>
                                                <span>Libros</span>
                                            </label>
                                        </div>
                                        <div class="category-option" data-category="activities">
                                            <input type="radio" id="category_activities" name="category" value="activities" 
                                                   {{ old('category') == 'activities' ? 'checked' : '' }} required>
                                            <label for="category_activities">
                                                <i class="fas fa-running"></i>
                                                <span>Actividades</span>
                                            </label>
                                        </div>
                                        <div class="category-option" data-category="transport">
                                            <input type="radio" id="category_transport" name="category" value="transport" 
                                                   {{ old('category') == 'transport' ? 'checked' : '' }} required>
                                            <label for="category_transport">
                                                <i class="fas fa-bus"></i>
                                                <span>Transporte</span>
                                            </label>
                                        </div>
                                        <div class="category-option" data-category="other">
                                            <input type="radio" id="category_other" name="category" value="other" 
                                                   {{ old('category') == 'other' ? 'checked' : '' }} required>
                                            <label for="category_other">
                                                <i class="fas fa-receipt"></i>
                                                <span>Otros</span>
                                            </label>
                                        </div>
                                    </div>
                                    @error('category')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Notas -->
                                <div class="form-group-modern">
                                    <label for="notes" class="form-label-modern">
                                        <i class="fas fa-sticky-note"></i> Notas Adicionales
                                    </label>
                                    <textarea class="form-control-modern @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="Agrega cualquier información adicional...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Botones -->
                                <div class="row mt-4">
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('payments.index') }}" class="action-button btn-cancel">
                                            <i class="fas fa-arrow-left me-2"></i> Volver
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" class="action-button btn-create">
                                            <i class="fas fa-magic me-2"></i> Crear Pago
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Selector de categorías interactivo
    const categoryOptions = document.querySelectorAll('.category-option');
    categoryOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remover selección anterior
            categoryOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Seleccionar esta opción
            this.classList.add('selected');
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Efecto de ondas
            createRippleEffect(this, event);
        });
        
        // Verificar si está seleccionado al cargar
        const radio = option.querySelector('input[type="radio"]');
        if (radio.checked) {
            option.classList.add('selected');
        }
    });

    // Animación de enfoque en inputs
    const inputs = document.querySelectorAll('.form-control-modern');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 5px 15px rgba(102, 126, 234, 0.2)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Animación del formulario al enviar
    const form = document.getElementById('paymentForm');
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('.btn-create');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creando...';
        submitBtn.disabled = true;
        
        // Crear efecto de ondas en el botón
        createRippleEffect(submitBtn, e);
    });

    // Validación en tiempo real del monto
    const amountInput = document.getElementById('amount');
    amountInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value > 0) {
            this.style.borderColor = '#4facfe';
        } else if (this.value === '') {
            this.style.borderColor = 'rgba(255, 255, 255, 0.3)';
        } else {
            this.style.borderColor = '#f5576c';
        }
    });

    // Función para crear efecto de ondas
    function createRippleEffect(element, event) {
        const ripple = document.createElement('span');
        const rect = element.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        element.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    // Efecto hover mejorado para botones
    const actionButtons = document.querySelectorAll('.action-button');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.05)';
        });
        
        button.addEventListener('mouseleave', function() {
            if (!this.disabled) {
                this.style.transform = 'translateY(0) scale(1)';
            }
        });
    });
});
</script>
@endpush