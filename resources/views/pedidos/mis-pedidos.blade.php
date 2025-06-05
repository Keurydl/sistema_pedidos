@extends('layouts.app')

@section('title', 'Mis Pedidos - Sistema de Pedidos')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mis Pedidos</h1>
    
    @if($pedidos->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td>#{{ $pedido->id }}</td>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td>${{ number_format($pedido->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $pedido->estado == 'completado' ? 'success' : ($pedido->estado == 'pendiente' ? 'warning' : 'info') }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-sm btn-primary">Ver detalles</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            <p>No tienes pedidos realizados todavía.</p>
        </div>
        <a href="{{ route('productos') }}" class="btn btn-primary">Ver productos</a>
    @endif
</div>
@endsection