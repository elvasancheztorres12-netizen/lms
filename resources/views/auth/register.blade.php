@extends('layouts.auth')

@section('title', 'Registro - Systematic')

@section('content')
    <div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
        <div class="col-md-5">

            <div class="text-center mb-4">
                <img src="{{ asset('images/Systematic_logo.png') }}" width="140">
                <h4 class="mt-3 brand">Crea tu cuenta</h4>
                <p class="text-muted">Regístrate para acceder a tus cursos y evaluaciones.</p>
            </div>

            <div class="card login-card p-4 shadow-sm">
                <form method="POST" action="{{ route('register.submit') }}">
                    @csrf

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Nombres</label>
                        <input type="text" name="first_names" value="{{ old('first_names') }}" 
                               class="form-control" placeholder="Tus nombres" maxlength="20" required>
                        @error('first_names')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="last_names" value="{{ old('last_names') }}" 
                               class="form-control" placeholder="Tus apellidos" maxlength="20" required>
                        @error('last_names')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Número de Documento (DNI)</label>
                        <input type="text" name="document_number" value="{{ old('document_number') }}" 
                               class="form-control" placeholder="8 dígitos numéricos" 
                               maxlength="8" minlength="8" pattern="\d{8}" title="El DNI debe tener exactamente 8 dígitos"
                               inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        @error('document_number')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="form-control" placeholder="nombre@ejemplo.com" maxlength="150" required>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="text" name="username" value="{{ old('username') }}" 
                               class="form-control" placeholder="Ej: jpereda" maxlength="50"
                               oninput="this.value = this.value.replace(/\s/g, '')" required>
                        @error('username')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Mínimo 6 caracteres" minlength="6" required>
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Repite tu contraseña" minlength="6" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Registrarse
                    </button>
                </form>
            </div>

            <p class="text-center mt-3 text-muted">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
            </p>

        </div>
    </div>
@endsection