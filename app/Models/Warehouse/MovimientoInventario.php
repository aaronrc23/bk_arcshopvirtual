<?php

namespace App\Models\Warehouse;


use App\Models\Warehouse\Inventario;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'mov_inventarios';

    protected $fillable = [
        'inventario_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'descripcion',
        'user_id',
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
