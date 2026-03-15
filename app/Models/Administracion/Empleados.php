<?php

namespace App\Models\Administracion;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleados extends Model
{
    use SoftDeletes;

    protected $table = 'empleados';
    protected $fillable = [
        'id',
        'user_id',
        'phone',
        'dni',
        'direccion',
        'genero',
        'codigo_postal',
        'departamento',
        'provincia',
        'distrito',
        'referencia',
        'photo',

    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
