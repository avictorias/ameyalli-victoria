<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    // Nombre de la tabla en la base de datos
    protected $table = 'producto';

    // Campos que se pueden rellenar de forma masiva (Mass Assignment)
    protected $fillable = [
        'sku',
        'nombre',
        'descripcion_corta',
        'descripcion_larga',
        'precio_usd',
        'precio_mxn',
        'imagen',
        'stock',
        'fecha_vigencia',
        'activo',
    ];

    // Relación con los detalles de los pedidos (un producto puede estar en muchos detalles de pedido)
    public function detallesPedido()
    {
        return $this->hasMany(PedidoDetalle::class, 'producto_id');
    }
}