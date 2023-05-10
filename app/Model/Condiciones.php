<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Condiciones extends Model
{
    protected $table = 'condiciones';

    protected $fillable = [
        'descripcion'
    ];
}
