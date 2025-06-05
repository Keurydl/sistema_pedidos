@extends('layouts.app')

@section('title', 'Crear Categoría')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Crear Nueva Categoría</h1>
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
            <form action="{{ route('admin.categorias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de la Categoría</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen de la Categoría</label>
                    <input type="file" class="form-control" id="imagen" name="imagen">
                    <div class="form-text">La imagen es opcional. Si no se proporciona, se usará una imagen predeterminada.</div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection