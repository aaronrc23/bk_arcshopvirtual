<?php

namespace App\Models\Logistica;

use App\Models\Catalogos\TipoAfectacion;
use App\Models\Catalogos\Unidades;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productos extends Model
{
    use SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'name',
        'description',
        'valor_unitario',
        'precio_venta',
        'precio_compra',
        'valor_mayoreo',
        'precio_mayoreo',
        'codigo_barras',
        'codigo_interno',
        'afecto_icbper',
        'factor_icbper',
        'tipo_afectacion_id',
        'categoria_id',
        'unidad_id',
        'activo',
        'destacado',
        'cantidad_mayoreo',
    ];

    protected $casts = [
        'valor_unitario' => 'decimal:3',
        'precio_venta' => 'decimal:3',
        'precio_compra' => 'decimal:3',
        'valor_mayoreo' => 'decimal:3',
        'precio_mayoreo' => 'decimal:3',
        'factor_icbper' => 'decimal:2',
        'afecto_icbper' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function tipoAfectacion(): BelongsTo
    {
        return $this->belongsTo(TipoAfectacion::class, 'tipo_afectacion_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidades::class, 'unidad_id');
    }

    // public function almacen(): HasMany
    // {
    //     return $this->hasMany(Inventario::class, 'product_id');
    // }

    public function imagenes()
    {
        return $this->hasMany(ProductoImg::class, 'producto_id');
    }
}
