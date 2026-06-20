<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use SoftDeletes;

    protected $table = 'banners';

    protected $fillable = [
        'titulo',
        'subtitulo',
        'url_imagen',
        'enlace',
        'orden',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Accesor para obtener la URL completa de la imagen
     */
    public function getUrlImagenAttribute(?string $value): ?string
    {
        return $value
            ? Storage::disk('public')->url($value)
            : null;
    }

    /**
     * Scope para banners activos ordenados
     */
    public function scopeActivos($query)
    {
        return $query->where('is_active', true)->orderBy('orden');
    }
}
