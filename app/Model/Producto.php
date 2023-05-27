<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    protected $fillable = ['nombre','id_marca','id_tipo_producto', 'es_de_venta'];


    public function compras(){
    	return $this->hasMany('App\Model\DetalleCompraProductos','id_producto','id_producto');
    }

    public function marca(){
        return $this->hasOne('App\Model\Marca','id','id_marca');        
    }

    public function tipo_producto()
    {
        return Tipo_Producto::where('id', $this->id_tipo_producto)->first();
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
