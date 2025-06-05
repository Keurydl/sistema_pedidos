<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Pago;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    // Remove the constructor with middleware
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
    */
    
    public function index()
    {
        // Get all payments for the current user
        $pagos = Pedido::with('detalles.producto')
            ->where('user_id', auth()->id())
            ->where('estado', '!=', 'cancelado')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pagos.index', compact('pagos'));
    }
    
    public function show($id)
    {
        // Get specific payment details
        $pago = Pedido::with('detalles.producto')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('pagos.show', compact('pago'));
    }

    public function destroy($id)
    {
        // Assuming 'Pago' refers to a 'Pedido' in this context
        $pago = Pedido::where('user_id', auth()->id())->findOrFail($id);

        // Add any payment-specific cancellation logic here if needed
        // For example, interacting with a payment gateway to refund, or changing status

        // If a 'Pago' is just a 'Pedido', deleting it might be similar to PedidoController@destroy
        // If there are associated DetallePedido and stock adjustments, consider that logic here or ensure it's handled by model events/observers if appropriate.
        // For simplicity, we'll delete the Pedido record directly.
        // If cascade on delete is set up for `detalle_pedidos` in the database, they will be deleted too.
        // Otherwise, you might need to delete them manually: $pago->detalles()->delete();

        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado con éxito.');
    }
}