@extends('layouts.app')

@section('title', 'Mis Pedidos - Sistema de Pedidos')

@section('styles')
<style>
    :root {
        --royal-blue: #002349;
        --gold: #957C3D;
        --light-gold: #c4aa6a;
        --very-light-gold: #f5eeda;
    }
    
    h1 {
        color: var(--royal-blue);
        position: relative;
        padding-bottom: 10px;
        margin-bottom: 25px;
    }
    
    h1::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background-color: var(--gold);
    }
    
    .table thead {
        background-color: var(--royal-blue);
        color: white;
    }
    
    .btn-primary {
        background-color: var(--gold) !important;
        border-color: var(--gold) !important;
    }
    
    .btn-primary:hover {
        background-color: var(--light-gold) !important;
        border-color: var(--light-gold) !important;
    }
    
    .alert-info {
        background-color: var(--very-light-gold);
        border-color: var(--light-gold);
        color: var(--royal-blue);
    }
    
    .badge.bg-success {
        background-color: #28a745 !important;
    }
    
    .badge.bg-warning {
        background-color: var(--gold) !important;
    }
    
    .badge.bg-danger {
        background-color: #dc3545 !important;
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--gold) !important;
        border-color: var(--gold) !important;
    }
    
    .pagination .page-link {
        color: var(--royal-blue) !important;
    }
    
    .pagination .page-item.active .page-link {
        color: white !important;
    }
</style>
@endsection

@section('content')
<div class="container">
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
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td>${{ number_format($pedido->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $pedido->estado == 'completado' ? 'success' : ($pedido->estado == 'pendiente' ? 'warning' : 'info') }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $pedidos->links() }}
    @else
        <div class="alert alert-info">
            <p>No tienes pedidos realizados aún.</p>
        </div>
        <a href="{{ route('productos') }}" class="btn btn-primary">Ver Productos</a>
    @endif
</div>
@endsection