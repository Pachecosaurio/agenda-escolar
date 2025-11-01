{{-- Formulario de creación de Tarea. --}}
@extends('layouts.app')

@push('styles')
<style>
    /* Estilos consistentes con Editar Tarea */
    .modern-card {
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        background: var(--bs-card-bg, #fff);
        transition: box-shadow 0.3s;
    }
    .modern-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.18); }
    .modern-input {
        border: 1px solid #e0e0e0;
        border-radius: 1rem;
        background: var(--bs-body-bg, #f8f9fa);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .modern-input:focus { border-color: #6c63ff; box-shadow: 0 0 0 2px #6c63ff33; }
    .modern-btn { border-radius: 1rem; font-weight: 500; box-shadow: 0 2px 8px rgba(108,99,255,0.08); transition: background 0.2s, color 0.2s; }
    .modern-btn:hover { background: #6c63ff; color: #fff; }
    .modern-toast { border-radius: 1rem; background: #ffe0e0; color: #d32f2f; border: none; box-shadow: 0 2px 8px rgba(211,47,47,0.08); }
    .modern-feedback { color: #d32f2f; font-size: 0.95em; border-radius: 0.5rem; background: #fff0f0; padding: 0.3em 0.7em; margin-top: 0.2em; }
    .hero-tasks { background: linear-gradient(135deg, #667eea, #764ba2); color:#fff; padding: 2.25rem 0; position:relative; overflow:hidden; }
    .hero-tasks .title { font-weight:800; }
    .hero-tasks .subtitle { opacity:.9; }
</style>
@endpush

@section('content')
<div class="hero-tasks">
    <div class="container">
        <h1 class="title mb-1"><i class="fa-solid fa-plus me-2"></i>Nueva tarea</h1>
        <p class="subtitle mb-0">Crea una tarea con fecha, adjunto y estado</p>
    </div>
</div>
<div class="container py-5" style="background: linear-gradient(135deg, #e0e7ff 0%, #f3f6fd 100%); min-height: 100vh; margin-top: 0;">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-4 p-5 modern-card">
                <h2 class="text-center fw-bold mb-4" style="font-size:1.85rem; color:#2563eb;">Nueva tarea</h2>
                @if($errors->any())
                    <div class="alert alert-danger rounded-4 shadow-sm modern-toast mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold" style="color:#374151;">
                            <i class="fa-solid fa-heading me-1"></i> Título de la tarea
                        </label>
                        <input type="text" class="form-control form-control-lg modern-input" id="title" name="title" value="{{ old('title') }}" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold" style="color:#374151;">
                            <i class="fa-solid fa-align-left me-1"></i> Descripción
                        </label>
                        <textarea class="form-control form-control-lg modern-input" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="due_date" class="form-label fw-bold" style="color:#374151;">
                            <i class="fa-solid fa-calendar-days me-1"></i> Fecha de vencimiento
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg modern-input" id="due_date" name="due_date" value="{{ old('due_date') }}">
                    </div>
                    <div class="mb-4">
                        <label for="attachment" class="form-label fw-bold"><i class="fa-solid fa-paperclip me-1"></i> Adjuntar archivo <span class="text-muted">(opcional)</span></label>
                        <input type="file" class="form-control form-control-lg modern-input" id="attachment" name="attachment">
                    </div>
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" value="1" id="completed" name="completed" {{ old('completed') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="completed">Marcar como completada</label>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5 shadow modern-btn">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow modern-btn">
                            <i class="fa-solid fa-save"></i> Guardar tarea
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
