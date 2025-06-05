<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Session;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = Session::get('carrito', []);
        $total = 0;
        
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        return view('carrito.index', compact('carrito', 'total'));
    }
    
    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);
        
        $producto = Producto::findOrFail($request->producto_id);
        
        // Verificar stock
        if ($producto->stock < $request->cantidad) {
            return redirect()->back()->with('error', 'No hay suficiente stock disponible.');
        }
        
        $carrito = Session::get('carrito', []);
        
        // Si el producto ya está en el carrito, actualizar cantidad
        if (isset($carrito[$producto->id])) {
            $carrito[$producto->id]['cantidad'] += $request->cantidad;
        } else {
            // Si no, agregar nuevo item al carrito
            $carrito[$producto->id] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $request->cantidad,
                'imagen' => $producto->imagen,
            ];
        }
        
        Session::put('carrito', $carrito);
        
        return redirect()->route('carrito')->with('success', 'Producto agregado al carrito correctamente.');
    }
    
    public function actualizar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);
        
        $carrito = Session::get('carrito', []);
        
        if (isset($carrito[$request->producto_id])) {
            $producto = Producto::findOrFail($request->producto_id);
            
            // Verificar stock
            if ($producto->stock < $request->cantidad) {
                return redirect()->back()->with('error', 'No hay suficiente stock disponible.');
            }
            
            $carrito[$request->producto_id]['cantidad'] = $request->cantidad;
            Session::put('carrito', $carrito);
        }
        
        return redirect()->route('carrito')->with('success', 'Carrito actualizado correctamente.');
    }
    
    public function eliminar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
        ]);
        
        $carrito = Session::get('carrito', []);
        
        if (isset($carrito[$request->producto_id])) {
            unset($carrito[$request->producto_id]);
            Session::put('carrito', $carrito);
        }
        
        return redirect()->route('carrito')->with('success', 'Producto eliminado del carrito.');
    }
    
    // Make sure the vaciar method is properly implemented
    public function vaciar(Request $request)
    {
        Session::forget('carrito');
        return redirect()->route('carrito')->with('success', 'El carrito ha sido vaciado correctamente.');
    }
    
    // Update the checkout method to handle the payment process
    public function checkout()
    {
        $carrito = Session::get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito')->with('error', 'No hay productos en el carrito.');
        }
        
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        return view('carrito.checkout', compact('carrito', 'total'));
    }
}