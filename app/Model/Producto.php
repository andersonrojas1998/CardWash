<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    protected $fillable = ['nombre','id_marca','id_tipo_producto','id_unidad_de_medida'];

    public function marca()
    {
        return Marca::where('id', $this->id_marca)->first();
    }

    public function tipo_producto()
    {
        return Tipo_Producto::where('id', $this->id_tipo_producto)->first();
    }

    public function unidad_de_medida()
    {
        return UnidadDeMedida::where('id', $this->id_unidad_de_medida)->first();
    }
}
