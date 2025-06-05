@extends('layouts.app')

@section('title', 'Categorías - Sistema de Pedidos')

@section('styles')
<style>
    :root {
        --royal-blue: #002349;
        --gold: #957C3D;
        --light-gold: #c4aa6a;
        --very-light-gold: #f5eeda;
    }
    
    .main-container {
        max-width: 1500px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .page-title {
        color: var(--royal-blue);
        font-size: 28px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    
    /* Amazon-style grid layout */
    .category-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }
    
    @media (max-width: 1200px) {
        .category-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .category-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 576px) {
        .category-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Amazon-style category card */
    .category-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    
    .category-header {
        background-color: var(--royal-blue);
        color: white;
        padding: 15px;
        font-weight: 600;
    }
    
    .category-img-container {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background-color: #f8f9fa;
    }
    
    .category-img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    
    .category-content {
        padding: 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .category-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--royal-blue);
        margin-bottom: 10px;
    }
    
    .category-description {
        color: #555;
        font-size: 14px;
        margin-bottom: 15px;
        flex-grow: 1;
    }
    
    .category-link {
        display: inline-block;
        background-color: var(--gold);
        color: white;
        padding: 8px 15px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        text-align: center;
        transition: background-color 0.2s;
    }
    
    .category-link:hover {
        background-color: var(--light-gold);
        color: white;
    }
    
    /* Amazon-style section */
    .amazon-section {
        margin-bottom: 40px;
    }
    
    .section-title {
        font-size: 21px;
        color: var(--royal-blue);
        margin-bottom: 15px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="main-container">
    <h1 class="page-title">Explora por categoría</h1>
    
    @if($categorias->count() > 0)
        <!-- Technology Section -->
        <div class="amazon-section">
            <h2 class="section-title">Tecnología</h2>
            <div class="category-grid">
                @foreach($categorias as $categoria)
                <div class="category-card">
                    <div class="category-img-container">
                        <img src="{{ asset($categoria->imagen) }}" alt="{{ $categoria->nombre }}" class="category-img" onerror="this.src='{{ asset('assets/img/categories/default.jpg') }}'">
                    </div>
                    <div class="category-content">
                        <h3 class="category-title">{{ $categoria->nombre }}</h3>
                        <p class="category-description">{{ $categoria->descripcion }}</p>
                        <a href="{{ route('categorias.show', $categoria->slug) }}" class="category-link">Ver todos</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <p>No hay categorías disponibles en este momento.</p>
        </div>
    @endif
</div>
@endsection