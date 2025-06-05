@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Productos</h1>
    
    @if(isset($error))
        <div class="alert alert-warning">
            {{ $error }}
        </div>
    @else
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="{{ route('productos.index') }}" method="GET" class="d-flex">
                    <input type="text" name="busqueda" class="form-control me-2" placeholder="Buscar productos..." value="{{ $busqueda ?? '' }}">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>
            <div class="col-md-4">
                <form action="{{ route('productos.index') }}" method="GET">
                    <select name="categoria" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ (isset($categoria_id) && $categoria_id == $categoria->id) ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        
        @if($productos->count() > 0)
            <div class="row">
                @foreach($productos as $producto)
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
                @endforeach
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $productos->links() }}
            </div>
        @else
            <div class="alert alert-info">
                No se encontraron productos.
            </div>
        @endif
    @endif
</div>
@endsection