<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido_detalle extends Model

{
    use HasFactory;

    protected $table = 'pedido_detalle';

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_usd',
        'precio_mxn',
    ];

    // Pertenece a un pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    // Pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }
}

