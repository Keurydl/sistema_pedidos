@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Usuario</h1>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Usuarios
        </a>
    </div>
    
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $usuario->name }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="is_admin" class="form-label">Rol</label>
                    <select class="form-select" id="is_admin" name="is_admin" required>
                        <option value="0" {{ $usuario->is_admin == 0 ? 'selected' : '' }}>Usuario</option>
                        <option value="1" {{ $usuario->is_admin == 1 ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection