@extends('layouts.app')

@section('title', 'Editar Pedido #' . $pedido->id . ' - Sistema de Pedidos')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.pedidos.index') }}">Pedidos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Pedido #{{ $pedido->id }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Editar Pedido #{{ $pedido->id }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pedidos.update', $pedido->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado del Pedido <span class="text-danger">*</span></label>
                            <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="pendiente" {{ old('estado', $pedido->estado) === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="procesando" {{ old('estado', $pedido->estado) === 'procesando' ? 'selected' : '' }}>Procesando</option>
                                <option value="enviado" {{ old('estado', $pedido->estado) === 'enviado' ? 'selected' : '' }}>Enviado</option>
                                <option value="entregado" {{ old('estado', $pedido->estado) === 'entregado' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelado" {{ old('estado', $pedido->estado) === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion_envio" class="form-label">Dirección de Envío <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('direccion_envio') is-invalid @enderror" id="direccion_envio" name="direccion_envio" rows="3" required>{{ old('direccion_envio', $pedido->direccion_envio) }}</textarea>
                            @error('direccion_envio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                            <select class="form-select @error('metodo_pago') is-invalid @enderror" id="metodo_pago" name="metodo_pago" required>
                                <option value="tarjeta" {{ old('metodo_pago', $pedido->metodo_pago) === 'tarjeta' ? 'selected' : '' }}>Tarjeta de Crédito/Débito</option>
                                <option value="transferencia" {{ old('metodo_pago', $pedido->metodo_pago) === 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                <option value="efectivo" {{ old('metodo_pago', $pedido->metodo_pago) === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            </select>
                            @error('metodo_pago')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Pedido</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detalles del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio Unitario</th>
                                    <th>Cantidad</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->detalles as $detalle)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ !empty($detalle->producto->imagen) ? asset('uploads/productos/' . $detalle->producto->imagen) : asset('assets/img/no-image.jpg') }}" 
                                                 alt="{{ $detalle->producto->nombre }}" style="width: 50px; height: 50px; object-fit: cover;" class="me-2">
                                            <div>
                                                {{ $detalle->producto->nombre }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td class="text-end">${{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end">${{ number_format($pedido->total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection