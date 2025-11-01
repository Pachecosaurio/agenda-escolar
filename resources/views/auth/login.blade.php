@extends('layouts.app')

@push('styles')
<style>
    .dark-mode .login-title {
        color: #fff176 !important;
        font-size: 2.2rem;
        font-weight: bold;
        letter-spacing: 1px;
    }
</style>
@endpush

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background: linear-gradient(135deg, #e0e7ff 0%, #f3f6fd 100%);">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 rounded-4 p-5" style="background:rgba(255,255,255,0.95);">
            <div class="text-center mb-4">
                <span class="rounded-circle bg-primary-subtle p-3 mb-2 login-icon">
                    <i class="fa-solid fa-right-to-bracket fa-2x"></i>
                </span>
                <h2 class="fw-bold login-title">Iniciar sesión</h2>
            </div>
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label fw-bold">Correo electrónico</label>
                    <input id="email" type="email" class="form-control form-control-lg shadow-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus style="border-radius: 1rem;">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Contraseña</label>
                    <input id="password" type="password" class="form-control form-control-lg shadow-sm @error('password') is-invalid @enderror" name="password" required style="border-radius: 1rem;">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Recordarme
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary fw-bold">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100 shadow" style="border-radius: 1rem; font-weight: bold;">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Entrar
                </button>
            </form>
            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-decoration-none text-secondary fw-bold">
                    ¿No tienes cuenta? <span class="text-primary">Regístrate</span>
                </a>
            </div>
        </div>
    </div>
@endsection