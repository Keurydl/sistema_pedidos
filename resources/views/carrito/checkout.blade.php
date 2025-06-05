@extends('layouts.app')

@section('title', 'Finalizar Compra')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Finalizar Compra</h1>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Información de Envío</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pedidos.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ auth()->user()->nombre ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" value="{{ auth()->user()->apellido ?? '' }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" value="{{ auth()->user()->telefono ?? '' }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ auth()->user()->direccion ?? '' }}" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" value="{{ auth()->user()->ciudad ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="codigo_postal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="{{ auth()->user()->codigo_postal ?? '' }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas adicionales (opcional)</label>
                            <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3">Método de Pago</h5>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="tarjeta" value="tarjeta" checked>
                            <label class="form-check-label" for="tarjeta">
                                Tarjeta de Crédito/Débito
                            </label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="paypal" value="paypal">
                            <label class="form-check-label" for="paypal">
                                PayPal
                            </label>
                        </div>
                        <div id="tarjeta-details" class="mb-4">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="numero_tarjeta" class="form-label">Número de Tarjeta</label>
                                    <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" placeholder="XXXX XXXX XXXX XXXX">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fecha_expiracion" class="form-label">Fecha de Expiración</label>
                                    <input type="text" class="form-control" id="fecha_expiracion" name="fecha_expiracion" placeholder="MM/AA">
                                </div>
                                <div class="col-md-6">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" placeholder="XXX">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-lock me-2"></i> Completar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($carrito as $item)
                        <li class="list-group-item d-flex justify-content-between lh-sm">
                            <div>
                                <h6 class="my-0">{{ $item['nombre'] }} ({{ $item['cantidad'] }})</h6>
                                <small class="text-muted">{{ Str::limit($item['descripcion'] ?? '', 50) }}</small>
                            </div>
                            <span class="text-muted">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</span>
                        </li>
                        @endforeach
                    </ul>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Envío</span>
                        <strong>Gratis</strong>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('carrito') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-arrow-left me-2"></i> Volver al Carrito
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle payment method details
    document.addEventListener('DOMContentLoaded', function() {
        const tarjetaRadio = document.getElementById('tarjeta');
        const paypalRadio = document.getElementById('paypal');
        const efectivoRadio = document.getElementById('efectivo');
        const tarjetaDetails = document.getElementById('tarjeta-details');
        
        function togglePaymentDetails() {
            if (tarjetaRadio.checked) {
                tarjetaDetails.style.display = 'block';
            } else {
                tarjetaDetails.style.display = 'none';
            }
        }
        
        tarjetaRadio.addEventListener('change', togglePaymentDetails);
        paypalRadio.addEventListener('change', togglePaymentDetails);
        efectivoRadio.addEventListener('change', togglePaymentDetails);
        
        // Initial state
        togglePaymentDetails();
    });
</script>
@endpush
@endsection