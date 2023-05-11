<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';

    protected $fillable = [
        'reg_op',
        'fecha_emision',
        'compracol',
        'fecha_vencimiento',
        'no_comprobante',
        'id_proveedor',
        'razon_social_proveedor',
        'descuentos_iva',
        'importe_total',
        'condiciones_id'
    ];

    protected $attributes = [
        'id_estado' => 1,
        'compra_o_gasto' => 'Compra'
    ];

    public function estado()
    {
        return Estado::where('id', $this->id_estado)->first();
    }

    public function condiciones()
    {
        return Condiciones::where('id', $this->condiciones_id)->first();
    }
}
