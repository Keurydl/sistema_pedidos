@extends('layouts.app')

@section('title', 'Detalles del Pago')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Pago #{{ $pago->id }}</h1>
        <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Mis Pagos
        </a>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Productos Comprados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pago->detalles as $detalle)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($detalle->producto)
                                            <img src="{{ asset($detalle->producto->imagen) }}" alt="{{ $detalle->producto->nombre }}" class="img-thumbnail me-3" style="width: 60px;">
                                            <span>{{ $detalle->producto->nombre }}</span>
                                            @else
                                            <span>Producto no disponible</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>${{ number_format($detalle->precio, 2) }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>${{ number_format($detalle->precio * $detalle->cantidad, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($pago->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información del Pago</h5>
                </div>
                <div class="card-body">
                    <p><strong>Estado:</strong> 
                        @if($pago->estado == 'pendiente')
                        <span class="badge bg-warning">Pendiente</span>
                        @elseif($pago->estado == 'procesando')
                        <span class="badge bg-info">Procesando</span>
                        @elseif($pago->estado == 'enviado')
                        <span class="badge bg-primary">Enviado</span>
                        @elseif($pago->estado == 'entregado')
                        <span class="badge bg-success">Entregado</span>
                        @elseif($pago->estado == 'cancelado')
                        <span class="badge bg-danger">Cancelado</span>
                        @endif
                    </p>
                    <p><strong>Fecha:</strong> {{ $pago->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Método de Pago:</strong> 
                        @if($pago->metodo_pago == 'tarjeta')
                        Tarjeta de Crédito/Débito
                        @elseif($pago->metodo_pago == 'paypal')
                        PayPal
                        @elseif($pago->metodo_pago == 'efectivo')
                        Efectivo al recibir
                        @endif
                    </p>
                    <p><strong>Total:</strong> ${{ number_format($pago->total, 2) }}</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Información de Envío</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ $pago->nombre }} {{ $pago->apellido }}</p>
                    <p><strong>Email:</strong> {{ $pago->email }}</p>
                    <p><strong>Teléfono:</strong> {{ $pago->telefono }}</p>
                    <p><strong>Dirección:</strong> {{ $pago->direccion }}</p>
                    <p><strong>Ciudad:</strong> {{ $pago->ciudad }}</p>
                    <p><strong>Código Postal:</strong> {{ $pago->codigo_postal }}</p>
                    
                    @if($pago->notas)
                    <p><strong>Notas:</strong> {{ $pago->notas }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection