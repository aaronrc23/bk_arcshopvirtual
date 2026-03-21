<?php

namespace App\Models\Administracion;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';

    protected $fillable = [
        'id',
        'tipodoc',
        'codLocal',
        'ruc',
        'razon_social',
        'nombre_comercial',
        'logo_path',
        'direccion',
        'pais',
        'departamento',
        'provincia',
        'distrito',
        'ubigeo',
        'email',
        'certificado',
        'usuario_sol',
        'clave_sol',
        'urbanizacion',
        'api_id',
        'api_clave',
        'username_api',
        'password_api',
        'estado_api',
        'token_api',
        'cert_path',
        'refresh_token_api',
        'estado',
    ];
    public $timestamps = false;

    public function empleados()
    {
        return $this->hasMany(Empresa::class);
    }
}
