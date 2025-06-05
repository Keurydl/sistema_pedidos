@extends('layouts.app')

@section('title', 'Iniciar Sesión - Sistema de Pedidos')

@section('styles')
<style>
    .login-container {
        max-width: 450px;
        margin: 60px auto;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card-header {
        background-color: var(--royal-blue);
        color: white;
        text-align: center;
        padding: 1.5rem;
        border-bottom: 4px solid var(--gold);
    }
    
    .card-header h3 {
        margin-bottom: 0;
        font-weight: 600;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    .form-control:focus {
        border-color: var(--light-gold);
        box-shadow: 0 0 0 0.25rem rgba(149, 124, 61, 0.25);
    }
    
    .btn-login {
        background-color: var(--gold);
        border-color: var(--gold);
        color: white;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        width: 100%;
        margin-top: 1rem;
    }
    
    .btn-login:hover {
        background-color: var(--light-gold);
        border-color: var(--light-gold);
    }
    
    .login-footer {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .login-footer a {
        color: var(--royal-blue);
        text-decoration: none;
        font-weight: 500;
    }
    
    .login-footer a:hover {
        color: var(--gold);
    }
    
    .input-group-text {
        background-color: var(--royal-blue);
        color: white;
        border-color: var(--royal-blue);
    }
</style>
@endsection

@section('content')
<div class="container login-container">
    <div class="card">
        <div class="card-header">
            <h3>Iniciar Sesión</h3>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                
                <button type="submit" class="btn btn-login">Iniciar Sesión</button>
            </form>
            
            <div class="login-footer">
                <p>¿No tienes una cuenta? <a href="{{ route('registro') }}">Regístrate aquí</a></p>
                <p><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></p>
            </div>
        </div>
    </div>
</div>
@endsection