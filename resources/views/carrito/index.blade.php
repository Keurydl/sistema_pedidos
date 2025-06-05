@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Carrito de Compras</h1>
    
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
    
    @if(count($carrito) > 0)
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carrito as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset($item['imagen']) }}" alt="{{ $item['nombre'] }}" class="img-thumbnail me-3" style="width: 60px;">
                                    <span>{{ $item['nombre'] }}</span>
                                </div>
                            </td>
                            <td>${{ number_format($item['precio'], 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.actualizar') }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $item['id'] }}">
                                    <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" class="form-control form-control-sm" style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td>${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.eliminar') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $item['id'] }}">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>${{ number_format($total, 2) }}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between">
        <form action="{{ route('carrito.vaciar') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-trash"></i> Vaciar Carrito
            </button>
        </form>
        
        <a href="{{ route('carrito.checkout') }}" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> Proceder al Pago
        </a>
    </div>
    @else
    <div class="alert alert-info">
        <p>Tu carrito está vacío.</p>
        <a href="{{ route('productos.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-shopping-bag"></i> Ir a Comprar
        </a>
    </div>
    @endif
</div>
@endsection