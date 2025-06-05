@extends('layouts.app')

@section('title', 'Registro - Sistema de Pedidos')

@section('styles')
<style>
    .register-container {
        max-width: 550px;
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
    
    .btn-register {
        background-color: var(--gold);
        border-color: var(--gold);
        color: white;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        width: 100%;
        margin-top: 1rem;
    }
    
    .btn-register:hover {
        background-color: var(--light-gold);
        border-color: var(--light-gold);
    }
    
    .register-footer {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .register-footer a {
        color: var(--royal-blue);
        text-decoration: none;
        font-weight: 500;
    }
    
    .register-footer a:hover {
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
<div class="container register-container">
    <div class="card">
        <div class="card-header">
            <h3>Crear Cuenta</h3>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('registro.post') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus>
                    </div>
                    @error('nombre')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </span>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">
                    </div>
                    @error('telefono')
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
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">términos y condiciones</a></label>
                    @error('terms')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-register">Registrarse</button>
            </form>
            
            <div class="register-footer">
                <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Términos y Condiciones -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: var(--royal-blue); color: white;">
                <h5 class="modal-title" id="termsModalLabel">Términos y Condiciones</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>1. Aceptación de los Términos</h5>
                <p>Al registrarse en nuestro Sistema de Pedidos, usted acepta estar sujeto a estos Términos y Condiciones de Uso y a nuestra Política de Privacidad.</p>
                
                <h5>2. Descripción del Servicio</h5>
                <p>Sistema de Pedidos es una plataforma que permite a los usuarios realizar pedidos en línea de productos disponibles en nuestro catálogo.</p>
                
                <h5>3. Registro de Cuenta</h5>
                <p>Para utilizar ciertas funciones de nuestro servicio, debe registrarse y mantener una cuenta activa. Usted es responsable de mantener la confidencialidad de su contraseña y de todas las actividades que ocurran bajo su cuenta.</p>
                
                <h5>4. Privacidad</h5>
                <p>Su privacidad es importante para nosotros. Consulte nuestra Política de Privacidad para comprender cómo recopilamos, usamos y divulgamos información sobre usted.</p>
                
                <h5>5. Limitación de Responsabilidad</h5>
                <p>En ningún caso seremos responsables por daños indirectos, incidentales, especiales, consecuentes o punitivos, o por cualquier pérdida de beneficios o ingresos.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" style="background-color: var(--gold); border-color: var(--gold);" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
@endsection