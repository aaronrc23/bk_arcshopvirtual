<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almacen extends Model
{
    use SoftDeletes;

    protected $table = 'almacenes';

    protected $fillable = [
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

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'almacen_id'); // ✅ corregido
    }
}
