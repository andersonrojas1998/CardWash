<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DetalleVentaProductos extends Model
{
    protected $table = 'detalle_venta_productos';

    protected $fillable = [
        'id_venta',
        'id_producto',
        'cantidad',
        'precio_venta',
        'margen_ganancia'
    ];

    public function venta()
    {
        return $this->hasOne('App\Model\Venta', 'id', 'id_venta');
    }

    public function producto()
    {
        return $this->hasOne('App\Model\Producto', 'id', 'id_producto');
    }
}
