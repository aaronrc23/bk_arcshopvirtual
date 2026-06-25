<?php

namespace App\Models\Logistica;

use App\Models\Catalogos\TipoAfectacion;
use App\Models\Catalogos\Unidades;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Productos extends Model
{
    use SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'name',
        'slug',
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
        'marca_id',
        'activo',
        'destacado',
        'cantidad_mayoreo',
    ];

    protected static function booted()
    {
        static::creating(function ($producto) {
            if (empty($producto->slug)) {
                $producto->slug = \Illuminate\Support\Str::slug($producto->name);
            }
        });

        static::updating(function ($producto) {
            if ($producto->isDirty('name') && !$producto->isDirty('slug')) {
                $producto->slug = \Illuminate\Support\Str::slug($producto->name);
            }
        });
    }

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

    public function imagenPrincipal()
    {
        return $this->hasOne(ProductoImg::class, 'producto_id')
            ->where('is_principal', true);
    }


    public function marca()
    {
        return $this->belongsTo(Marcas::class, 'marca_id');
    }

    public function presentaciones(): HasMany
    {
        return $this->hasMany(Presentaciones::class, 'producto_id');
    }

    public function caracteristicas(): HasMany
    {
        return $this->hasMany(Caracteristicas::class, 'producto_id');
    }
}
