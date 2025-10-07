@extends('layouts.app')

@push('styles')
<style>
    /* Estilos solo modo claro para creación de tareas */
    .container {
        position: relative;
        z-index: 1;
    }
    
    /* Asegurar que las cards no interfieran con la navegación */
    .card {
        position: relative;
        z-index: auto;
    }
</style>
@endpush

@section('content')
<div class="container py-5" style="background: linear-gradient(135deg, #e0e7ff 0%, #f3f6fd 100%); min-height: 100vh; margin-top: 20px;">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-4 p-5 modern-card">
                <h1 class="text-center fw-bold mb-4" style="font-size:2rem; color:#2563eb;">
                    <span class="rounded-circle bg-primary-subtle p-3 me-2">
                        <i class="fa-solid fa-plus fa-2x text-primary"></i>
                    </span>
                    Nueva tarea
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
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold" style="color:#374151;">
                            <i class="fa-solid fa-heading me-1"></i> Título de la tarea
                        </label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm" id="title" name="title" value="{{ old('title') }}" required style="border:2px solid #e5e7eb; background:#f9fafb;">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold" style="color:#374151;">
                            <i class="fa-solid fa-align-left me-1"></i> Descripción
                        </label>
                        <textarea class="form-control form-control-lg rounded-3 shadow-sm" id="description" name="description" rows="4" style="border:2px solid #e5e7eb; background:#f9fafb;">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="due_date" class="form-label fw-bold" style="color:#374151;">
                            <i class="fa-solid fa-calendar-days me-1"></i> Fecha de vencimiento
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg rounded-3 shadow-sm" id="due_date" name="due_date" value="{{ old('due_date') }}" style="border:2px solid #e5e7eb; background:#f9fafb;">
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
