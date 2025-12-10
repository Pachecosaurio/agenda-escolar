{{-- Vista de detalle de Tarea --}}
@extends('layouts.app')

@push('styles')
<style>
    .modern-card {
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        background: var(--bs-card-bg, #fff);
        transition: box-shadow 0.3s;
    }
    .modern-card:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }
    .hero-tasks { 
        background: linear-gradient(135deg, #667eea, #764ba2); 
        color:#fff; 
        padding: 2.25rem 0; 
        position:relative; 
        overflow:hidden; 
    }
    .hero-tasks .title { font-weight:800; }
    .hero-tasks .subtitle { opacity:.9; }
    .detail-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .detail-value {
        font-size: 1.1rem;
        color: #212529;
        margin-bottom: 1.5rem;
    }
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .status-completed {
        background: #d4edda;
        color: #155724;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
</style>
@endpush

@section('content')
<div class="hero-tasks">
    <div class="container">
        <h1 class="title mb-1"><i class="fa-solid fa-list-check me-2"></i>Detalle de Tarea</h1>
        <p class="subtitle mb-0">Información completa de la tarea</p>
    </div>
</div>

<div class="container py-5" style="background: linear-gradient(135deg, #e0e7ff 0%, #f3f6fd 100%); min-height: 100vh; margin-top: 0;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 p-5 modern-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">
                        <i class="fa-solid fa-clipboard-check text-primary me-2"></i>
                        {{ $task->title }}
                    </h2>
                    <div>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary btn-sm rounded-pill me-2">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">
                            <i class="fa-solid fa-calendar-days me-2"></i>Fecha de vencimiento
                        </div>
                        <div class="detail-value">
                            @if($task->due_date)
                                {{ $task->due_date->format('d/m/Y') }}
                                <small class="text-muted">
                                    ({{ $task->due_date->diffForHumans() }})
                                </small>
                            @else
                                <span class="text-muted">Sin fecha</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-label">
                            <i class="fa-solid fa-flag me-2"></i>Estado
                        </div>
                        <div class="detail-value">
                            @if($task->completed)
                                <span class="status-badge status-completed">
                                    <i class="fa-solid fa-check-circle me-1"></i>Completada
                                </span>
                            @else
                                <span class="status-badge status-pending">
                                    <i class="fa-solid fa-clock me-1"></i>Pendiente
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="detail-label">
                    <i class="fa-solid fa-align-left me-2"></i>Descripción
                </div>
                <div class="detail-value">
                    @if($task->description)
                        <p class="mb-0">{{ $task->description }}</p>
                    @else
                        <span class="text-muted">Sin descripción</span>
                    @endif
                </div>

                @if($task->attachment)
                    <div class="detail-label">
                        <i class="fa-solid fa-paperclip me-2"></i>Archivo adjunto
                    </div>
                    <div class="detail-value">
                        <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                            <i class="fa-solid fa-download me-1"></i>
                            Descargar adjunto
                        </a>
                    </div>
                @endif

                <hr class="my-4">

                <div class="row text-muted small">
                    <div class="col-md-6">
                        <i class="fa-solid fa-clock me-1"></i>
                        Creada: {{ $task->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-6 text-md-end">
                        <i class="fa-solid fa-edit me-1"></i>
                        Última actualización: {{ $task->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-pen me-2"></i>Editar tarea
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta tarea?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                            <i class="fa-solid fa-trash me-2"></i>Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
