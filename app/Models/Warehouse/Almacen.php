<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almacen extends Model
{
    use SoftDeletes;

    protected $table = 'almacenes';

    protected $fillable = [
        'id',
        'code',
        'nombre',
        'descripcion',
        'tipo',
        'activo',
        'is_principal'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'is_principal' => 'boolean',
    ];


}
