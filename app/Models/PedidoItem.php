<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    /**
     * Get the order that owns the item.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Get the product for this item.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}