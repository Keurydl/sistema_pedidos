@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container py-5">
    <h1>Panel de Administración</h1>
    <p class="lead">Bienvenido al panel de administración del Sistema de Pedidos.</p>

    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h2 class="card-title">Productos</h2>
                    <p class="display-4">{{ $totalProductos }}</p>
                    <a href="{{ route('admin.productos.index') }}" class="btn btn-light">Gestionar Productos</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h2 class="card-title">Categorías</h2>
                    <p class="display-4">{{ $totalCategorias }}</p>
                    <a href="{{ route('admin.categorias.index') }}" class="btn btn-light">Gestionar Categorías</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h2 class="card-title">Usuarios</h2>
                    <p class="display-4">{{ $totalUsuarios }}</p>
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-light">Gestionar Usuarios</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            Acciones Rápidas
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.productos.create') }}" class="btn btn-outline-primary">
                    <i class="fas fa-plus"></i> Añadir Producto
                </a>
                <a href="{{ route('admin.categorias.create') }}" class="btn btn-outline-success">
                    <i class="fas fa-plus"></i> Añadir Categoría
                </a>
                <a href="#" class="btn btn-outline-info">
                    <i class="fas fa-chart-bar"></i> Ver Estadísticas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
