@extends('layouts.app')

@section('title', $categoria->nombre . ' - Sistema de Pedidos')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $categoria->nombre }}</h1>
    
    <p class="text-muted">{{ $categoria->descripcion }}</p>
    
    @if($productos->isEmpty())
    <div class="alert alert-warning">
        <p>No hay productos disponibles en esta categoría.</p>
    </div>
    @else
    <div class="row">
        @foreach($productos as $producto)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="{{ asset($producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $producto->nombre }}</h5>
                    <p class="card-text">{{ Str::limit($producto->descripcion, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0">${{ number_format($producto->precio, 2) }}</span>
                        <a href="{{ route('producto.detalle', $producto->id) }}" class="btn btn-primary">Ver detalles</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $productos->links() }}
    </div>
    @endif
    
    <div class="mt-4">
        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver a categorías
        </a>
    </div>
</div>
@endsection