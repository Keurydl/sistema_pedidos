@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4">Contacto</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <form action="{{ route('contacto.enviar') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="asunto" class="form-label">Asunto</label>
                    <input type="text" class="form-control @error('asunto') is-invalid @enderror" id="asunto" name="asunto" value="{{ old('asunto') }}" required>
                    @error('asunto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea class="form-control @error('mensaje') is-invalid @enderror" id="mensaje" name="mensaje" rows="5" required>{{ old('mensaje') }}</textarea>
                    @error('mensaje')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información de Contacto</h5>
                    <p class="card-text">Si tienes alguna pregunta o comentario, no dudes en contactarnos.</p>
                    
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> keurydd@outlook.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +1 (809) 990-3199</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Santiago de los caballeros, República Dominicana</li>
                    </ul>
                    
                    <h5 class="mt-4">Conéctate conmigo</h5>
                    <div class="mt-3">
                        <a href="https://www.linkedin.com/in/keury-david-deschamps-lopez-3891181b1/" target="_blank" class="btn btn-outline-primary me-2">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                        <a href="mailto:keurydd@gmail.com" target="_blank" class="btn btn-outline-danger me-2">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                        <a href="https://github.com/Keurydl" target="_blank" class="btn btn-outline-dark">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection