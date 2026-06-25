<?php

namespace App\Models\Logistica;

use App\Enums\Categorylevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Categorias extends Model
{
    use SoftDeletes;

    protected $table = 'categorias';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'level',
        'parent_id',
        'icon',
        'imagen',
        'order',
        'is_active',
    ];

    protected $casts = [
        'level' => Categorylevel::class,
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = \Illuminate\Support\Str::slug($categoria->name);
            }
        });

        static::updating(function ($categoria) {
            if ($categoria->isDirty('name') && !$categoria->isDirty('slug')) {
                $categoria->slug = \Illuminate\Support\Str::slug($categoria->name);
            }
        });
    }



    // 🔁 Relación padre (recursiva)
    public function parentCategory()
    {
        return $this->belongsTo(Categorias::class, 'parent_id');
    }

    // 🌿 Hijos
    public function children()
    {
        return $this->hasMany(Categorias::class, 'parent_id');
    }

    // 📦 Productos
    public function productos()
    {
        return $this->hasMany(Productos::class, 'categoria_id');
    }
}
