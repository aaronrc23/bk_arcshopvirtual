<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marcas extends Model
{
    use SoftDeletes;

    protected $table = 'marcas';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'imagen',
        'estado',
    ];


    public function productos()
    {
        return $this->hasMany(Productos::class, 'marca_id');
    }
}
