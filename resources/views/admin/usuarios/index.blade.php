@extends('layouts.app')

@section('title', 'Administrar Usuarios')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Administrar Usuarios</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Fecha de registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @if($usuario->is_admin == 1)
                                    <span class="badge bg-primary">Administrador</span>
                                @else
                                    <span class="badge bg-secondary">Usuario</span>
                                @endif
                            </td>
                            <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-info btn-sm me-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(auth()->id() != $usuario->id)
                                    <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection