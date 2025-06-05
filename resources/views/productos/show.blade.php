@extends('layouts.app')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $producto->nombre }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-md-6">
            <!-- Check if the image exists and display it properly -->
            @if($producto->imagen && file_exists(public_path($producto->imagen)))
                <img src="{{ asset($producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-fluid">
            @else
                <img src="{{ asset('img/productos/default.jpg') }}" alt="{{ $producto->nombre }}" class="img-fluid">
            @endif
        </div>
        <div class="col-md-6">
            <h1 class="mb-3">{{ $producto->nombre }}</h1>
            <p class="text-muted">Categoría: {{ $producto->categoria->nombre }}</p>
            <p class="fs-4 fw-bold text-primary">${{ number_format($producto->precio, 2) }}</p>
            <p>{{ $producto->descripcion }}</p>
            
            <form action="{{ route('carrito.agregar') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                <div class="mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" value="1" min="1" max="{{ $producto->stock }}">
                </div>
                <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
            </form>
        </div>
    </div>
    
    @if($productosRelacionados->count() > 0)
        <div class="mt-5">
            <h3 class="mb-4">Productos Relacionados</h3>
            <div class="row">
                @foreach($productosRelacionados as $productoRelacionado)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="{{ $productoRelacionado->imagen ?? 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="{{ $productoRelacionado->nombre }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $productoRelacionado->nombre }}</h5>
                                <p class="card-text fw-bold">${{ number_format($productoRelacionado->precio, 2) }}</p>
                                <a href="{{ route('productos.show', $productoRelacionado->id) }}" class="btn btn-outline-primary">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection