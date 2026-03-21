<?php

namespace App\Models\Logistica;

use App\Enums\Categorylevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorias extends Model
{
    use SoftDeletes;

    protected $table = 'categorias';

    protected $fillable = [
        'id',
        'name',
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
