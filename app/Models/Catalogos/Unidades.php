<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{
    protected $table = 'unidades';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'descripcion',
    ];
}
