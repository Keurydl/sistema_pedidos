<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ProductoController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        // Check if the productos and categorias tables exist
        $hasProductos = Schema::hasTable('productos');
        $hasCategorias = Schema::hasTable('categorias');
        
        if (!$hasProductos || !$hasCategorias) {
            return view('productos.index', [
                'productos' => collect([]),
                'categorias' => collect([]),
                'error' => 'Las tablas necesarias no existen. Por favor, contacte al administrador.'
            ]);
        }
        
        // Get all categories
        $categorias = Categoria::all();
        
        // Filter products by category if specified
        $categoria_id = $request->input('categoria');
        $query = Producto::query();
        
        if ($categoria_id) {
            $query->where('categoria_id', $categoria_id);
        }
        
        // Search products by name if specified
        $busqueda = $request->input('busqueda');
        if ($busqueda) {
            $query->where('nombre', 'like', '%' . $busqueda . '%')
                  ->orWhere('descripcion', 'like', '%' . $busqueda . '%');
        }
        
        // Get the products with pagination
        $productos = $query->paginate(12);
        
        return view('productos.index', compact('productos', 'categorias', 'categoria_id', 'busqueda'));
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        $productosRelacionados = Producto::where('categoria_id', $producto->categoria_id)
                                        ->where('id', '!=', $producto->id)
                                        ->take(4)
                                        ->get();
        
        return view('productos.show', compact('producto', 'productosRelacionados'));
    }

    /**
     * Método temporal para crear el producto iPhone 15 Pro
     */
    public function crearIphone()
    {
        $categoria = \App\Models\Categoria::where('slug', 'smartphones')->first();
        
        if (!$categoria) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se encontró la categoría de smartphones');
        }
        
        $producto = \App\Models\Producto::updateOrCreate(
            ['nombre' => 'iPhone 15 Pro'],
            [
                'slug' => 'iphone-15-pro',
                'descripcion' => 'El iPhone 15 Pro es el smartphone más avanzado de Apple. Cuenta con un potente chip A17 Pro, pantalla Super Retina XDR de 6.1 pulgadas, sistema de cámara Pro de 48MP, y está fabricado en titanio para mayor durabilidad.',
                'precio' => 999.99,
                'stock' => 10,
                'imagen' => '/uploads/productos/iphone15pro.jpg',
                'categoria_id' => $categoria->id
            ]
        );
        
        return redirect()->route('categorias.show', $categoria->slug)
            ->with('success', 'Producto iPhone 15 Pro creado correctamente');
    }

    /**
     * Método temporal para crear el producto Rolex 
     */
    public function crearRolex()
    {
        $categoria = \App\Models\Categoria::where('slug', 'relojes')->first();
        
        if (!$categoria) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se encontró la categoría de relojes');
        }
        
        // Primero, eliminamos el producto "Rolex Daytona" si existe
        \App\Models\Producto::where('nombre', 'Rolex Daytona')->delete();
        
        // Luego creamos o actualizamos el producto "Rolex"
        $producto = \App\Models\Producto::updateOrCreate(
            ['nombre' => 'Rolex'],
            [
                'slug' => 'rolex',
                'descripcion' => 'El Rolex Cosmograph Daytona es un reloj icónico diseñado para pilotos de carreras. Con su cronógrafo de alta precisión y escala taquimétrica, permite a los conductores medir velocidades promedio de hasta 400 kilómetros por hora. Fabricado en acero inoxidable con bisel de cerámica negra y esfera blanca con contadores negros.',
                'precio' => 14999.99,
                'stock' => 5,
                'imagen' => 'uploads/productos/rolex.png',
                'categoria_id' => $categoria->id
            ]
        );
        
        return redirect()->route('categorias.show', $categoria->slug)
            ->with('success', 'Producto Rolex creado correctamente');
    }
}