<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'usuario_id',
        'monto',
        'metodo_pago',
        'estado',
        'referencia',
        'fecha_pago'
    ];

    /**
     * Get the order that owns the payment.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Get the user that made the payment.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}