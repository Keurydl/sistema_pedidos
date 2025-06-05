@extends('layouts.app')

@section('title', 'Administrar Categorías')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Administrar Categorías</h1>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
            <a href="{{ route('admin.categorias.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Añadir Categoría
            </a>
        </div>
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
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Slug</th>
                            <th>Productos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias as $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td>
                                <img src="{{ asset($categoria->imagen ?? 'img/categorias/default.jpg') }}" alt="{{ $categoria->nombre }}" class="img-thumbnail" style="width: 60px;">
                            </td>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->slug }}</td>
                            <td>{{ $categoria->productos->count() }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.categorias.edit', $categoria->id) }}" class="btn btn-info btn-sm me-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categorias.destroy', $categoria->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta categoría?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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