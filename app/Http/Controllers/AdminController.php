<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $totalProductos = Producto::count();
        $totalCategorias = Categoria::count();
        $totalUsuarios = User::count();
        
        return view('admin.dashboard', compact(
            'totalProductos',
            'totalCategorias',
            'totalUsuarios'
        ));
    }
}