<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\UpdateEmpresaRqt;
use App\services\Administracion\EmpresaService;

class EmpresaController extends Controller
{
    public function __construct(
        protected EmpresaService $service
    ) {}

    /**
     * Obtener datos de la empresa
     */
    public function index()
    {
        return response()->json(
            $this->service->getEmpresa()
        );
    }

    /**
     * Actualizar datos de la empresa
     */
    public function update(int $id, UpdateEmpresaRqt $request)
    {
        return response()->json(
            $this->service->updateEmpresa($id, $request->validated())
        );
    }
}
