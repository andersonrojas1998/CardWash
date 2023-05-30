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
        return $this->hasOne('App\Model\Producto', 'id', 'id_producto');
    }

    public function unidad_de_medida()
    {
        return $this->hasOne('App\Model\UnidadDeMedida', 'id', 'id_unidad_de_medida');
    }

    public function compra(){
        return $this->belongsTo('App\Model\Compra', 'id');
    }
}
