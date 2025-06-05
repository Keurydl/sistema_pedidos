@extends('layouts.app')

@section('title', 'Crear Producto')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Crear Nuevo Producto</h1>
        <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Productos
        </a>
    </div>
    
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" value="{{ old('precio') }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" min="0" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required>{{ old('descripcion') }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen del Producto</label>
                            <input type="file" class="form-control" id="imagen" name="imagen">
                            <div class="form-text">La imagen es opcional. Si no se proporciona, se usará una imagen predeterminada.</div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection