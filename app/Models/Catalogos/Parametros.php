<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Parametros extends Model
{
    protected $table = 'tabla_parametrica';

    protected $fillable = [
        'tipo',
        'descripcion',
        'codigo',
    ];
}
