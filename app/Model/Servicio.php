<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'servicio';

    protected $fillable = [
        'nombre'
    ];

    public function detalle_paquete()
    {
        return $this->belongsTo('App\Model\DetallePaquete', 'id', 'id_servicio');
    }
}
