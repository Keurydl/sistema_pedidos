@extends('layouts.app')

@section('title', 'Recuperar Contraseña - Sistema de Pedidos')

@section('styles')
<style>
    .password-reset-container {
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
    
    .btn-reset {
        background-color: var(--gold);
        border-color: var(--gold);
        color: white;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        width: 100%;
        margin-top: 1rem;
    }
    
    .btn-reset:hover {
        background-color: var(--light-gold);
        border-color: var(--light-gold);
    }
    
    .reset-footer {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .reset-footer a {
        color: var(--royal-blue);
        text-decoration: none;
        font-weight: 500;
    }
    
    .reset-footer a:hover {
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
<div class="container password-reset-container">
    <div class="card">
        <div class="card-header">
            <h3>Recuperar Contraseña</h3>
        </div>
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            
            <p class="mb-4">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
            
            <form method="POST" action="{{ route('password.email') }}">
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
                
                <button type="submit" class="btn btn-reset">Enviar Enlace</button>
            </form>
            
            <div class="reset-footer">
                <p><a href="{{ route('login') }}"><i class="fas fa-arrow-left me-1"></i> Volver a Iniciar Sesión</a></p>
            </div>
        </div>
    </div>
</div>
@endsection