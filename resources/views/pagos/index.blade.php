@extends('layouts.app')

@section('title', 'Mis Pagos')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mis Pagos</h1>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if(count($pagos) > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pago #</th>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagos as $pago)
                        <tr>
                            <td>{{ $pago->id }}</td>
                            <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($pago->detalles->count() > 0)
                                    {{ $pago->detalles->first()->producto->nombre }}
                                    @if($pago->detalles->count() > 1)
                                        <span class="text-muted"> y {{ $pago->detalles->count() - 1 }} producto(s) más</span>
                                    @endif
                                @else
                                    <span class="text-muted">Sin productos</span>
                                @endif
                            </td>
                            <td>
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
                            </td>
                            <td>${{ number_format($pago->total, 2) }}</td>
                            <td>
                                <a href="{{ route('pagos.show', $pago->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Ver Detalles
                                </a>
                                <form action="{{ route('pagos.destroy', $pago->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este pago?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <p>No tienes pagos registrados aún.</p>
        <a href="{{ route('productos.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-shopping-bag"></i> Ver productos
        </a>
    </div>
    @endif
</div>
@endsection