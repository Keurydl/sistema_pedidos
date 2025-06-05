<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PedidoController extends Controller
{
    // Instead of using middleware in the constructor, apply it in the routes file
    // Remove this constructor
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
    */
    
    public function index()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pedidos.index', compact('pedidos'));
    }
    
    public function show($id)
    {
        $pedido = Pedido::with('detalles.producto')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('pedidos.show', compact('pedido'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:20',
            'metodo_pago' => 'required|in:tarjeta,paypal,efectivo',
        ]);
        
        $carrito = Session::get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito')->with('error', 'No hay productos en el carrito.');
        }
        
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        DB::beginTransaction();
        
        try {
            // Crear pedido
            $pedido = new Pedido();
            $pedido->user_id = auth()->id();
            $pedido->total = $total;
            $pedido->estado = 'pendiente';
            $pedido->metodo_pago = $request->metodo_pago;
            $pedido->nombre = $request->nombre;
            $pedido->apellido = $request->apellido;
            $pedido->email = $request->email;
            $pedido->telefono = $request->telefono;
            $pedido->direccion = $request->direccion;
            $pedido->ciudad = $request->ciudad;
            $pedido->codigo_postal = $request->codigo_postal;
            $pedido->notas = $request->notas;
            $pedido->save();
            
            // Crear detalles del pedido
            foreach ($carrito as $item) {
                $detalle = new DetallePedido();
                $detalle->pedido_id = $pedido->id;
                $detalle->producto_id = $item['id'];
                $detalle->cantidad = $item['cantidad'];
                $detalle->precio = $item['precio'];
                $detalle->save();
                
                // Actualizar stock
                $producto = Producto::find($item['id']);
                $producto->stock -= $item['cantidad'];
                $producto->save();
            }
            
            // Vaciar carrito
            Session::forget('carrito');
            
            DB::commit();
            
            return redirect()->route('pedidos.show', $pedido->id)
                ->with('success', '¡Pedido realizado con éxito! Tu número de pedido es: ' . $pedido->id);
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Ha ocurrido un error al procesar tu pedido. Por favor, inténtalo de nuevo.');
        }
    }
    
    public function destroy($id)
    {
        $pedido = Pedido::where('user_id', auth()->id())->findOrFail($id);

        // Optional: Restore stock before deleting order details and order
        // foreach ($pedido->detalles as $detalle) {
        //     $producto = Producto::find($detalle->producto_id);
        //     if ($producto) {
        //         $producto->stock += $detalle->cantidad;
        //         $producto->save();
        //     }
        // }

        // DetallePedido records should be deleted automatically if foreign key constraints are set up with onDelete('cascade')
        // Otherwise, delete them manually:
        // $pedido->detalles()->delete(); 

        $pedido->delete();

        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado con éxito.');
    }
    
    public function misPedidos()
    {
        return $this->index();
    }
}