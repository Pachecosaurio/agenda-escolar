
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
    .modern-input {
        border: 1px solid #e0e0e0;
        border-radius: 1rem;
        background: var(--bs-body-bg, #f8f9fa);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .modern-input:focus {
        border-color: #6c63ff;
        box-shadow: 0 0 0 2px #6c63ff33;
    }
    .modern-btn {
        border-radius: 1rem;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(108,99,255,0.08);
        transition: background 0.2s, color 0.2s;
    }
    .modern-btn:hover {
        background: #6c63ff;
        color: #fff;
    }
    .modern-toast {
        border-radius: 1rem;
        background: #ffe0e0;
        color: #d32f2f;
        border: none;
        box-shadow: 0 2px 8px rgba(211,47,47,0.08);
    }
    .modern-feedback {
        color: #d32f2f;
        font-size: 0.95em;
        border-radius: 0.5rem;
        background: #fff0f0;
        padding: 0.3em 0.7em;
        margin-top: 0.2em;
    }
    /* Estilos solo modo claro para edición de tareas */
    .hero-tasks { background: linear-gradient(135deg, #667eea, #764ba2); color:#fff; padding: 2.25rem 0; position:relative; overflow:hidden; }
    .hero-tasks .title { font-weight:800; }
    .hero-tasks .subtitle { opacity:.9; }
</style>
@endpush

@section('content')
<div class="hero-tasks">
    <div class="container">
        <h1 class="title mb-1"><i class="fa-solid fa-pen me-2"></i>Editar tarea</h1>
        <p class="subtitle mb-0">Actualiza título, descripción, fecha y adjuntos</p>
    </div>
</div>
<div class="container py-5" style="background: linear-gradient(135deg, #e0e7ff 0%, #f3f6fd 100%); min-height: 100vh; margin-top: 0;">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-4 p-5 modern-card">
                <h1 class="text-center fw-bold mb-4" style="font-size:2rem; color:#2563eb;">
                    <span class="rounded-circle bg-primary-subtle p-3 me-2">
                        <i class="fa-solid fa-pen fa-2x text-primary"></i>
                    </span>
                    Editar tarea
                </h1>
                @if($errors->any())
                    <div class="alert alert-danger rounded-4 shadow-sm modern-toast">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('tasks.update', $task) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">Título <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg modern-input @error('title') is-invalid @enderror" value="{{ old('title', $task->title) }}" required autofocus>
                        @error('title')
                            <div class="invalid-feedback modern-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Descripción</label>
                        <textarea name="description" id="description" class="form-control form-control-lg modern-input @error('description') is-invalid @enderror" rows="4">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback modern-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="attachment" class="form-label fw-bold">Adjuntar archivo <span class="text-muted">(opcional)</span></label>
                        <input type="file" class="form-control form-control-lg modern-input" id="attachment" name="attachment">
                        @if($task->attachment)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank" class="btn btn-outline-secondary btn-sm modern-btn px-3">
                                    <i class="fa-solid fa-paperclip"></i> Ver archivo adjunto
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" value="1" id="completed" name="completed" {{ $task->completed ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="completed">Marcar como completada</label>
                    </div>
                    <div class="mb-4">
                        <label for="due_date" class="form-label fw-bold">Fecha límite</label>
                        <input type="datetime-local" name="due_date" id="due_date" class="form-control form-control-lg modern-input @error('due_date') is-invalid @enderror" value="{{ old('due_date', $task->due_date ? date('Y-m-d\TH:i', strtotime($task->due_date)) : '') }}">
                        @error('due_date')
                            <div class="invalid-feedback modern-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-5 shadow modern-btn">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow modern-btn" style="font-weight:bold; font-size:1.05rem;">
                            <i class="fa-solid fa-floppy-disk"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
