<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        $productos = Producto::latest()->take(8)->get();
        $categorias = Categoria::all();
        
        return view('home', compact('productos', 'categorias'));
    }
}