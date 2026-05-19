<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presentaciones extends Model
{
    protected $table = 'presentaciones';

    protected $fillable = [
        'producto_id',
        'medida',
        'unidades_por_caja',
        'largo',
        'ancho',
        'alto',
        'peso',
        'unidad_id',
        'es_principal',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
