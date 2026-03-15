<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class TipoAfectacion extends Model
{
    protected $table = 'tipo_afectacion';
    protected $fillable = [
        'id',
        'clave',
        'nombre',
        'descripcion',
        'letra',
        'codigo',
        'tipo',

    ];
    public $timestamps = false;
}
