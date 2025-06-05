@extends('layouts.app')

@section('title', 'Realizar Pago - Sistema de Pedidos')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Mis Pedidos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pedidos.show', $pedido->id) }}">Pedido #{{ $pedido->id }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Realizar Pago</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Realizar Pago para el Pedido #{{ $pedido->id }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading">Información del Pedido</h5>
                        <p class="mb-0">Total a pagar: <strong>${{ number_format($pedido->total, 2) }}</strong></p>
                    </div>

                    <form action="{{ route('pagos.store', $pedido->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="metodo" class="form-label">Método de Pago <span class="text-danger">*</span></label>
                            <select class="form-select @error('metodo') is-invalid @enderror" id="metodo" name="metodo" required>
                                <option value="">Seleccione un método de pago</option>
                                <option value="tarjeta" {{ old('metodo') === 'tarjeta' ? 'selected' : '' }}>Tarjeta de Crédito/Débito</option>
                                <option value="transferencia" {{ old('metodo') === 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                <option value="efectivo" {{ old('metodo') === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            </select>
                            @error('metodo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div id="tarjeta-fields" class="payment-fields d-none">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Número de Tarjeta <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="card_number" placeholder="XXXX XXXX XXXX XXXX">
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="card_expiry" class="form-label">Fecha de Expiración <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="card_expiry" placeholder="MM/AA">
                                </div>
                                <div class="col-md-6">
                                    <label for="card_cvv" class="form-label">CVV <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="card_cvv" placeholder="123">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="card_name" class="form-label">Nombre en la Tarjeta <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="card_name" placeholder="NOMBRE APELLIDO">
                            </div>
                        </div>
                        
                        <div id="transferencia-fields" class="payment-fields d-none">
                            <div class="alert alert-info mb-3">
                                <h6 class="alert-heading">Información Bancaria</h6>
                                <p class="mb-0">Banco: Banco Nacional</p>
                                <p class="mb-0">Cuenta: 123-456789-0</p>
                                <p class="mb-0">Titular: Sistema de Pedidos S.A.</p>
                                <p class="mb-0">Referencia: Pedido #{{ $pedido->id }}</p>
                            </div>
                        </div>
                        
                        <div id="efectivo-fields" class="payment-fields d-none">
                            <div class="alert alert-info mb-3">
                                <p class="mb-0">El pago en efectivo se realizará al momento de la entrega.</p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="referencia" class="form-label">Referencia de Pago <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('referencia') is-invalid @enderror" id="referencia" name="referencia" value="{{ old('referencia') }}" required>
                            <div class="form-text">Para tarjetas: últimos 4 dígitos. Para transferencias: número de operación.</div>
                            @error('referencia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Confirmar Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const metodoSelect = document.getElementById('metodo');
        const paymentFields = document.querySelectorAll('.payment-fields');
        
        metodoSelect.addEventListener('change', function() {
            // Hide all payment fields
            paymentFields.forEach(field => {
                field.classList.add('d-none');
            });
            
            // Show the selected payment method fields
            const selectedMethod = this.value;
            if (selectedMethod) {
                document.getElementById(`${selectedMethod}-fields`).classList.remove('d-none');
            }
        });
        
        // Trigger change event to show fields for preselected method
        metodoSelect.dispatchEvent(new Event('change'));
    });
</script>
@endpush
@endsection