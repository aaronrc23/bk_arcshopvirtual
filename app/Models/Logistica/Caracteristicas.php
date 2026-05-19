<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;

class Caracteristicas extends Model
{
    protected $table = 'producto_caracteristicas';

    protected $fillable = [
        'producto_id',
        'descripcion',
        'orden',
    ];


    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
