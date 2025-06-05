<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    /**
     * Display a listing of all categories.
     */
    public function index()
    {
        // Verificar si hay categorías
        $categorias = Categoria::all();
        
        // Si no hay categorías, crear algunas por defecto
        if ($categorias->isEmpty()) {
            $this->crearCategoriasDefault();
            $categorias = Categoria::all();
        }
        
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Display products by category.
     */
    // Verifica que este método esté correctamente implementado
    public function show($slug)
    {
        try {
            $categoria = Categoria::where('slug', $slug)->firstOrFail();
            $productos = Producto::where('categoria_id', $categoria->id)->paginate(12);
            
            return view('categorias.show', compact('categoria', 'productos'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in CategoriaController@show: ' . $e->getMessage());
            
            // Return a friendly error page
            return redirect()->route('categorias.index')
                ->with('error', 'No se pudo cargar la categoría solicitada.');
        }
    }
    
    /**
     * Crear categorías por defecto si no existen
     */
    private function crearCategoriasDefault()
    {
        $categorias = [
            [
                'nombre' => 'Smartphones',
                'descripcion' => 'Teléfonos inteligentes de diversas marcas y modelos',
                'slug' => 'smartphones',
                'imagen' => 'assets/img/categories/smartphones.png'
            ],
            [
                'nombre' => 'Relojes',
                'descripcion' => 'Relojes inteligentes y tradicionales',
                'slug' => 'relojes',
                'imagen' => 'assets/img/categories/relojes.png'
            ],
            [
                'nombre' => 'Tablets',
                'descripcion' => 'Tablets de diferentes tamaños y marcas',
                'slug' => 'tablets',
                'imagen' => 'assets/img/categories/tablets.png'
            ],
            [
                'nombre' => 'Audífonos',
                'descripcion' => 'Audífonos con y sin cable, auriculares y headsets',
                'slug' => 'audifonos',
                'imagen' => 'assets/img/categories/audifonos.png'
            ],
            [
                'nombre' => 'Computadoras',
                'descripcion' => 'Computadoras de escritorio y portátiles',
                'slug' => 'computadoras',
                'imagen' => 'assets/img/categories/Computadoras.png'
            ],
            [
                'nombre' => 'Accesorios',
                'descripcion' => 'Discos duros, pantallas y otros accesorios',
                'slug' => 'accesorios',
                'imagen' => 'assets/img/categories/accesorios.png'
            ],
            [
                'nombre' => 'Consolas',
                'descripcion' => 'Consolas de videojuegos',
                'slug' => 'consolas',
                'imagen' => 'assets/img/categories/consolas.jpg'
            ],
            [
                'nombre' => 'Videojuegos',
                'descripcion' => 'Videojuegos para todas las plataformas',
                'slug' => 'videojuegos',
                'imagen' => 'assets/img/categories/videojuegos.jpg'
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::updateOrCreate(
                ['slug' => $categoria['slug']],
                $categoria
            );
        }
    }
}