@extends('layouts.app')

@section('content')
<div class="container-fluid hero-section py-5 text-center text-white">
    <div class="container py-5">
        <h1 class="display-4 mb-4">Bienvenido a nuestro Sistema de Pedidos</h1>
        <p class="lead mb-4">La mejor plataforma para gestionar tus pedidos en línea</p>
        <a href="{{ route('productos.index') }}" class="btn btn-primary btn-lg">Ver Productos</a>
    </div>
</div>

<div class="container py-5">
    <h2 class="text-center mb-5">Productos Destacados</h2>
    
    <div class="row">
        @forelse($productos as $producto)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="{{ $producto->imagen ?? 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="{{ $producto->nombre }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($producto->descripcion, 100) }}</p>
                        <p class="card-text fw-bold">${{ number_format($producto->precio, 2) }}</p>
                        <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-primary">Ver detalles</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No hay productos disponibles en este momento.
                </div>
            </div>
        @endforelse
    </div>

    <h2 class="text-center my-5">Categorías</h2>
    
    <div class="row">
        @forelse($categorias as $categoria)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $categoria->nombre }}</h5>
                        <p class="card-text">{{ Str::limit($categoria->descripcion ?? 'Explora nuestra selección de productos en esta categoría.', 100) }}</p>
                        <a href="{{ route('categorias.show', $categoria->slug ?? $categoria->id) }}" class="btn btn-outline-primary">Ver productos</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No hay categorías disponibles en este momento.
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .hero-section {
        background-color: var(--royal-blue);
        background-image: linear-gradient(135deg, var(--royal-blue) 0%, #1a237e 100%);
        margin-top: -1.5rem;
    }
</style>
@endsection
