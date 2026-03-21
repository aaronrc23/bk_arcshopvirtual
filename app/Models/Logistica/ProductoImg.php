<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductoImg extends Model
{
    use SoftDeletes;

    protected $table = 'productos_images';

    protected $fillable = [
        'url',
        'orden',
        'is_principal',
        'producto_id',
    ];

    protected $casts = [
        'is_principal' => 'boolean',
    ];

    // 🔗 Relación ManyToOne
    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
