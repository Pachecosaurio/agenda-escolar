@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card border-0 rounded-4 shadow-lg" style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px);">
                <!-- Header del formulario -->
                <div class="card-header bg-transparent border-0 text-center py-4">
                    <div class="icon-wrapper mx-auto mb-3" style="width: 80px; height: 80px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h1 class="h2 fw-bold text-dark mb-2">Editar Evento</h1>
                    <p class="text-muted mb-0">Modifica los detalles de tu evento</p>
                    @if($event->isRecurringParent())
                        <div class="badge bg-warning text-dark mt-2">
                            <i class="fas fa-repeat me-1"></i>Evento Recurrente Principal
                        </div>
                    @elseif($event->isGeneratedEvent())
                        <div class="badge bg-info text-white mt-2">
                            <i class="fas fa-link me-1"></i>Evento Generado
                        </div>
                    @endif
                </div>

                <div class="card-body px-5 pb-5">
                    @if($errors->any())
                        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                <strong>Errores de validación:</strong>
                            </div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($event->isGeneratedEvent())
                        <div class="alert alert-info border-0 rounded-3 shadow-sm mb-4">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            <strong>Nota:</strong> Este evento es parte de una serie recurrente. Los cambios solo afectarán a esta instancia específica.
                        </div>
                    @endif

                    <form action="{{ route('events.update', $event) }}" method="POST" id="eventForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Información básica -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <label for="title" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-tag text-primary me-2"></i>Título del evento
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" id="title" 
                                       class="form-control form-control-lg border-0 shadow-sm @error('title') is-invalid @enderror" 
                                       value="{{ old('title', $event->title) }}" required
                                       style="border-radius: 15px; background: rgba(248, 249, 250, 0.8);">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label for="description" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-align-left text-primary me-2"></i>Descripción
                                </label>
                                <textarea name="description" id="description" rows="3"
                                          class="form-control border-0 shadow-sm @error('description') is-invalid @enderror"
                                          style="border-radius: 15px; background: rgba(248, 249, 250, 0.8);">{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Fechas y horarios -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="start" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-play-circle text-success me-2"></i>Fecha y hora de inicio
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" name="start" id="start" 
                                       class="form-control border-0 shadow-sm @error('start') is-invalid @enderror" 
                                       value="{{ old('start', $event->start->format('Y-m-d\TH:i')) }}" required
                                       style="border-radius: 15px; background: rgba(248, 249, 250, 0.8);">
                                @error('start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="end" class="form-label fw-semibold text-dark">
                                    <i class="fas fa-stop-circle text-danger me-2"></i>Fecha y hora de fin
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" name="end" id="end" 
                                       class="form-control border-0 shadow-sm @error('end') is-invalid @enderror" 
                                       value="{{ old('end', $event->end ? $event->end->format('Y-m-d\TH:i') : '') }}" required
                                       style="border-radius: 15px; background: rgba(248, 249, 250, 0.8);">
                                @error('end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Opciones de repetición solo para eventos principales -->
                        @if(!$event->isGeneratedEvent())
                        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #f8f9ff 0%, #e8f0fe 100%); border-radius: 20px;">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-repeat text-primary me-2"></i>Opciones de Repetición
                                </h5>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" 
                                           value="1" {{ old('is_recurring', $event->is_recurring) ? 'checked' : '' }}
                                           style="transform: scale(1.2);">
                                    <label class="form-check-label fw-semibold" for="is_recurring">
                                        Repetir este evento
                                    </label>
                                </div>

                                <div id="recurrence-options" class="{{ old('is_recurring', $event->is_recurring) ? '' : 'd-none' }}">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="recurrence_type" class="form-label fw-semibold">Frecuencia</label>
                                            <select name="recurrence_type" id="recurrence_type" 
                                                    class="form-select border-0 shadow-sm"
                                                    style="border-radius: 12px; background: rgba(255, 255, 255, 0.9);">
                                                <option value="">Seleccionar...</option>
                                                <option value="daily" {{ old('recurrence_type', $event->recurrence_type) == 'daily' ? 'selected' : '' }}>Diariamente</option>
                                                <option value="weekly" {{ old('recurrence_type', $event->recurrence_type) == 'weekly' ? 'selected' : '' }}>Semanalmente</option>
                                                <option value="monthly" {{ old('recurrence_type', $event->recurrence_type) == 'monthly' ? 'selected' : '' }}>Mensualmente</option>
                                                <option value="yearly" {{ old('recurrence_type', $event->recurrence_type) == 'yearly' ? 'selected' : '' }}>Anualmente</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="recurrence_interval" class="form-label fw-semibold">Cada</label>
                                            <div class="input-group">
                                                <input type="number" name="recurrence_interval" id="recurrence_interval" 
                                                       class="form-control border-0 shadow-sm" 
                                                       value="{{ old('recurrence_interval', $event->recurrence_interval ?? 1) }}" min="1" max="52"
                                                       style="border-radius: 12px 0 0 12px; background: rgba(255, 255, 255, 0.9);">
                                                <span class="input-group-text border-0 shadow-sm" id="interval-label" 
                                                      style="border-radius: 0 12px 12px 0; background: rgba(255, 255, 255, 0.9);">día(s)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="recurrence_end_date" class="form-label fw-semibold">Finalizar repetición</label>
                                            <input type="date" name="recurrence_end_date" id="recurrence_end_date" 
                                                   class="form-control border-0 shadow-sm"
                                                   value="{{ old('recurrence_end_date', $event->recurrence_end_date?->format('Y-m-d')) }}"
                                                   style="border-radius: 12px; background: rgba(255, 255, 255, 0.9);">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="recurrence_count" class="form-label fw-semibold">Número de repeticiones</label>
                                            <input type="number" name="recurrence_count" id="recurrence_count" 
                                                   class="form-control border-0 shadow-sm"
                                                   value="{{ old('recurrence_count', $event->recurrence_count) }}" min="1" max="365"
                                                   placeholder="Opcional"
                                                   style="border-radius: 12px; background: rgba(255, 255, 255, 0.9);">
                                        </div>
                                    </div>

                                    @if($event->isRecurringParent() && $event->childEvents()->count() > 0)
                                        <div class="alert alert-warning border-0 rounded-3 mt-3" style="background: rgba(255, 193, 7, 0.1);">
                                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                            <small>Este evento tiene {{ $event->childEvents()->count() }} repeticiones generadas. Los cambios en la recurrencia regenerarán todas las repeticiones.</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Botones de acción -->
                        <div class="text-center pt-3">
                            <a href="{{ route('events.index') }}" class="btn btn-light btn-lg me-3 shadow-sm" style="border-radius: 25px; padding: 12px 30px;">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-lg shadow" 
                                    style="background: linear-gradient(45deg, #667eea, #764ba2); border: none; color: white; border-radius: 25px; padding: 12px 30px; font-weight: 600;">
                                <i class="fas fa-save me-2"></i>Actualizar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isRecurringCheckbox = document.getElementById('is_recurring');
    const recurrenceOptions = document.getElementById('recurrence-options');
    const recurrenceType = document.getElementById('recurrence_type');
    const intervalLabel = document.getElementById('interval-label');

    // Mostrar/ocultar opciones de repetición
    if (isRecurringCheckbox) {
        isRecurringCheckbox.addEventListener('change', function() {
            if (this.checked) {
                recurrenceOptions.classList.remove('d-none');
                recurrenceOptions.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                recurrenceOptions.classList.add('d-none');
                recurrenceOptions.classList.remove('animate__animated', 'animate__fadeIn');
            }
        });
    }

    // Actualizar label del intervalo según el tipo
    if (recurrenceType) {
        const updateIntervalLabel = function() {
            const labels = {
                'daily': 'día(s)',
                'weekly': 'semana(s)',
                'monthly': 'mes(es)',
                'yearly': 'año(s)'
            };
            intervalLabel.textContent = labels[recurrenceType.value] || 'día(s)';
        };

        recurrenceType.addEventListener('change', updateIntervalLabel);
        updateIntervalLabel(); // Ejecutar al cargar
    }
});
</script>

<style>
.form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    border-color: rgba(102, 126, 234, 0.5);
}

.form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    border-color: rgba(102, 126, 234, 0.5);
}

.icon-wrapper {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}
</style>
@endpush
@endsection
