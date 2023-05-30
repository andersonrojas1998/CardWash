<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    protected $fillable = ['nombre','id_marca','id_tipo_producto', 'es_de_venta'];

    public function marca()
    {
        return $this->hasOne('App\Model\Marca', 'id', 'id_marca');
    }

    public function tipo_producto()
    {
        return $this->hasOne('App\Model\Tipo_Producto', 'id', 'id_tipo_producto');
    }

    public function cantidad()
    {
        $compra = DetalleCompraProductos::where([
            ['id_producto', $this->id],
            ['compra_o_gasto', 'Compra']
        ])->join('compra', [
            ['compra.id','=','detalle_compra_productos.id_compra']
            ])->sum('cantidad');

        $gasto = DetalleCompraProductos::where([
            ['id_producto', $this->id],
            ['compra_o_gasto', 'Gasto']
        ])->join('compra', [
            ['compra.id','=','detalle_compra_productos.id_compra']
            ])->sum('cantidad');

        return $compra - $gasto;
    }
}
