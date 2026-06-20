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
        'descripcion',
        'logo_path',
        'direccion',
        'pais',
        'departamento',
        'provincia',
        'distrito',
        'ubigeo',
        'email',
        'telefono',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'whatsapp',
        'footer_links',
        'copyright_text',
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

    protected $casts = [
        'footer_links' => 'array',
        'estado' => 'boolean',
        'estado_api' => 'boolean',
    ];

    public $timestamps = false;

    public function empleados()
    {
        return $this->hasMany(Empresa::class);
    }
}
