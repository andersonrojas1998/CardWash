<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DetalleCompraProductos extends Model
{
    protected $table = "detalle_compra_productos";

    protected $fillable = [
        'id_producto',
        'id_compra',
        'id_unidad_de_medida',
        'cantidad',
        'precio_compra',
        'precio_venta'
    ];

    public function producto()
    {
        return Producto::where('id', $this->id_producto)->first();
    }

    public function unidad_de_medida()
    {
        return UnidadDeMedida::where('id', $this->id_unidad_de_medida)->first();
    }
}
