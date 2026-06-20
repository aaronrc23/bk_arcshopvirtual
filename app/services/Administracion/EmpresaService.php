<?php

namespace App\services\Administracion;

use App\Models\Administracion\Empresa;
use Illuminate\Support\Facades\DB;

class EmpresaService
{
    /**
     * Obtener datos de la empresa (primera empresa)
     */
    public function getEmpresa()
    {
        $empresa = Empresa::first();

        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'No hay empresa registrada',
            ], 404);
        }

        return $empresa;
    }

    /**
     * Actualizar datos de la empresa
     */
    public function updateEmpresa(int $id, array $data): array
    {
        return DB::transaction(function () use ($id, $data) {
            $empresa = Empresa::findOrFail($id);

            $empresa->update($data);

            return [
                'success' => true,
                'message' => 'Actualización exitosa',
                'detail' => 'Datos de la empresa actualizados con éxito',
            ];
        });
    }
}
