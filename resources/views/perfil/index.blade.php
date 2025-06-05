@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($usuario->name) }}&background=random&color=fff" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                    <h5 class="my-3">{{ $usuario->name }}</h5>
                    <p class="text-muted mb-1">{{ $usuario->email }}</p>
                    <div class="d-flex justify-content-center mb-2">
                        <a href="{{ route('perfil.edit') }}" class="btn btn-primary">Editar Perfil</a>
                        <a href="{{ route('perfil.change-password') }}" class="btn btn-outline-primary ms-1">Cambiar Contraseña</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Nombre Completo</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ $usuario->name }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ $usuario->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Teléfono</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ $usuario->telefono ?? 'No especificado' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Dirección</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ $usuario->direccion ?? 'No especificada' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Mis Pedidos Recientes</h5>
                    <div class="table-responsive">
                        @if(count($usuario->pedidos ?? []) > 0)
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
                                    @foreach($usuario->pedidos->take(5) as $pedido)
                                        <tr>
                                            <td>{{ $pedido->id }}</td>
                                            <td>{{ $pedido->created_at->format('d/m/Y') }}</td>
                                            <td>${{ number_format($pedido->total, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $pedido->estado == 'pendiente' ? 'warning' : ($pedido->estado == 'pagado' ? 'success' : 'info') }}">
                                                    {{ ucfirst($pedido->estado) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-sm btn-info">Ver</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="{{ route('pedidos.mis-pedidos') }}" class="btn btn-outline-primary">Ver todos mis pedidos</a>
                        @else
                            <p>No tienes pedidos recientes.</p>
                            <a href="{{ url('/') }}" class="btn btn-primary">Explorar productos</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection