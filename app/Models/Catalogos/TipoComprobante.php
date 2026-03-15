<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    protected $table = 'tipo_comprobante';


    protected $fillable = [
        'id',
        'codigo',
        'descripcion'
    ];
}
