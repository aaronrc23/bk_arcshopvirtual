<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidades extends Model
{
    use SoftDeletes;

    protected $table = '  unidades';

    protected $fillable = [
        'id',
        'descripcion'
    ];

    public function productos()
    {
        return $this->hasMany(Productos::class);
    }
}
