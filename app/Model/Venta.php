<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';

    protected $fillable = [
        'nombre_cliente',
        'placa',
        'numero_telefono',
        'id_detalle_paquete',
        'id_usuario',
        "id_estado_venta"
    ];

    public function setPlacaAttribute($value)
    {
        $this->attributes['placa'] = strtoupper($value);
    }

    public function detalle_venta_productos()
    {
        return $this->hasMany('App\Model\DetalleVentaProductos', 'id_venta', 'id');
    }

    public function detalle_paquete(){
        return $this->hasOne('App\Model\DetallePaquete', 'id', 'id_detalle_paquete');
    }

    public function estado_venta()
    {
        return $this->hasOne('App\Model\EstadoVenta', 'id', 'id_estado_venta');
    }

    public function user()
    {
        return $this->hasOne('App\Model\users', 'id', 'id_usuario');
    }
}
