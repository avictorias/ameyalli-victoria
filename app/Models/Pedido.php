<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'folio',
        'correo',
        'subtotal',
        'impuestos',
        'total',
        'estatus',
        'fecha',
    ];

    // Relación con los detalles del pedido
    public function detalles()
    {
        return $this->hasMany(Pedido_detalle::class, 'pedido_id');
    }
}