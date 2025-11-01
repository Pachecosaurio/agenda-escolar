@extends('layouts.app')

@push('styles')
<style>
    .dark-mode .register-title {
        color: #fff176 !important;
        font-size: 2.2rem;
        font-weight: bold;
        letter-spacing: 1px;
    }
</style>
@endpush

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background: linear-gradient(135deg, #f3f6fd 0%, #e0e7ff 100%);">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4 p-5" style="background:rgba(255,255,255,0.95);">
            <div class="text-center mb-4">
                <span class="rounded-circle bg-success-subtle p-3 mb-2 register-icon">
                    <i class="fa-solid fa-user-plus fa-2x"></i>
                </span>
                <h2 class="fw-bold register-title">Registrarse</h2>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Nombre</label>
                    <input id="name" type="text" class="form-control form-control-lg shadow-sm @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus style="border-radius: 1rem;">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold">Correo electrónico</label>
                    <input id="email" type="email" class="form-control form-control-lg shadow-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="border-radius: 1rem;">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Contraseña</label>
                    <input id="password" type="password" class="form-control form-control-lg shadow-sm @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" style="border-radius: 1rem;">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password-confirm" class="form-label fw-bold">Confirmar contraseña</label>
                    <input id="password-confirm" type="password" class="form-control form-control-lg shadow-sm" name="password_confirmation" required autocomplete="new-password" style="border-radius: 1rem;">
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100 shadow" style="border-radius: 1rem; font-weight: bold;">
                    <i class="fa-solid fa-user-plus"></i> Registrarse
                </button>
            </form>
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-decoration-none text-secondary fw-bold">
                    ¿Ya tienes cuenta? <span class="text-success">Inicia sesión</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
