<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'total', 'estado', 'metodo_pago',
        'nombre', 'apellido', 'email', 'telefono',
        'direccion', 'ciudad', 'codigo_postal', 'notas'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }
}