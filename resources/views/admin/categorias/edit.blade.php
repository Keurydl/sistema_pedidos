@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Editar Categoría</h1>
        <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Categorías
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
            <form action="{{ route('admin.categorias.update', $categoria->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen de la Categoría</label>
                            <input type="file" class="form-control mb-2" id="imagen" name="imagen">
                            <div class="text-center">
                                <p class="text-muted small">Imagen actual:</p>
                                <img src="{{ asset($categoria->imagen ?? 'img/categorias/default.jpg') }}" alt="{{ $categoria->nombre }}" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                            <p class="text-muted small mt-2">Deja este campo vacío si no quieres cambiar la imagen.</p>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection