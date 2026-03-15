<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tipo_documento';
    protected $fillable = [
        'id',
        'codigo',
        'descripcion',
        'estado'
    ];
    public $timestamps = false;
    // public function comprobante()
    // {
    //     return $this->belongsTo(Comprobantes::class, 'empresa_id');
    // }
}
