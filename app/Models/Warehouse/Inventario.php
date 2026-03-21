<?php

namespace App\Models\Warehouse;

use App\Models\Logistica\Productos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventario extends Model
{
    use SoftDeletes;
    protected $table = 'inventario';
    protected $fillable = [
        'product_id',
        'stock',
        'almacen_id',
        'min_stock',
        'max_stock',
        'estado',
    ];


    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'inventario_id');
    }


    public function producto()
    {
        return $this->belongsTo(Productos::class, 'product_id');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }
}
