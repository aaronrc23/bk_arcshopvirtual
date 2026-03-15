<?php

namespace App\Models\Auth;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profiles extends Model
{
    use SoftDeletes;
    protected $table = 'profiles';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id',
        'name',
        'apellidos',
        'dni',
        'telefono',
        'genero',
        'codigo_postal',
        'departamento',
        'provincia',
        'distrito',
        'referencia',
        'photo',
        'direccion',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
