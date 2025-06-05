@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mis Pedidos</h1>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if(count($pedidos) > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pedido #</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($pedido->estado == 'pendiente')
                                <span class="badge bg-warning">Pendiente</span>
                                @elseif($pedido->estado == 'procesando')
                                <span class="badge bg-info">Procesando</span>
                                @elseif($pedido->estado == 'enviado')
                                <span class="badge bg-primary">Enviado</span>
                                @elseif($pedido->estado == 'entregado')
                                <span class="badge bg-success">Entregado</span>
                                @elseif($pedido->estado == 'cancelado')
                                <span class="badge bg-danger">Cancelado</span>
                                @endif
                            </td>
                            <td>${{ number_format($pedido->total, 2) }}</td>
                            <td>
                                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Ver Detalles
                                </a>
                                <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">
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
        <p>No tienes pedidos realizados.</p>
        <a href="{{ route('productos.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-shopping-bag"></i> Ver productos
        </a>
    </div>
    @endif
</div>
@endsection