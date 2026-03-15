<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    protected $table = 'moneda';
    protected $fillable = [
        'id',
        'moneda',
        'descripcion',
        'abreviatura',
        'estado'
    ];
    public $timestamps = false;
}
