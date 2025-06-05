@extends('layouts.app')

@section('title', 'Editar Pago #' . $pago->id . ' - Sistema de Pedidos')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.pagos.index') }}">Pagos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Pago #{{ $pago->id }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Editar Pago #{{ $pago->id }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading">Información del Pedido #{{ $pago->pedido_id }}</h5>
                        <p class="mb-0">Cliente: <strong>{{ $pago->pedido->usuario->nombre }}</strong></p>
                        <p class="mb-0">Total: <strong>${{ number_format($pago->pedido->total, 2) }}</strong></p>
                        <p class="mb-0">Estado del pedido: 
                            <span class="badge bg-{{ 
                                $pago->pedido->estado === 'pendiente' ? 'warning' : 
                                ($pago->pedido->estado === 'procesando' ? 'info' : 
                                ($pago->pedido->estado === 'enviado' ? 'primary' : 
                                ($pago->pedido->estado === 'entregado' ? 'success' : 'danger'))) 
                            }}">
                                {{ ucfirst($pago->pedido->estado) }}
                            </span>
                        </p>
                    </div>

                    <form action="{{ route('admin.pagos.update', $pago->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="metodo" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                            <select class="form-select @error('metodo') is-invalid @enderror" id="metodo" name="metodo" required>
                                <option value="tarjeta" {{ old('metodo', $pago->metodo) === 'tarjeta' ? 'selected' : '' }}>Tarjeta de Crédito/Débito</option>
                                <option value="transferencia" {{ old('metodo', $pago->metodo) === 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                <option value="efectivo" {{ old('metodo', $pago->metodo) === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            </select>
                            @error('metodo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="referencia" class="form-label">Referencia de Pago <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('referencia') is-invalid @enderror" id="referencia" name="referencia" value="{{ old('referencia', $pago->referencia) }}" required>
                            @error('referencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado del Pago <span class="text-danger">*</span></label>
                            <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="pendiente" {{ old('estado', $pago->estado) === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="completado" {{ old('estado', $pago->estado) === 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="rechazado" {{ old('estado', $pago->estado) === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.pagos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection