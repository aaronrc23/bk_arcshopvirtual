<?php

namespace App\Models\Administracion;

use App\Models\Catalogos\TipoComprobante;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $table = 'serie';
    protected $fillable = [
        'id',
        'serie',
        'correlativo',
        'codigo',
        'sucursal_id',
        'tipo_comprobante_id',
        'estado'
    ];
    public $timestamps = false;
    public function tipocomprobante()
    {
        return $this->belongsTo(TipoComprobante::class, 'tipo_comprobante_id');
    }
}
