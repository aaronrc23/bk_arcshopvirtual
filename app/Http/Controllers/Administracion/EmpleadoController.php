<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\EmpleadoCreateRqt;
use App\Http\Requests\Administracion\EmpleadoUpdateRqt;
use App\Http\Resources\Administracion\EmpleadoResource;
use App\Models\Administracion\Empleados;
use App\services\Administracion\EmpleadoService;
use App\services\Administracion\UpdateEmpService;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    protected $empleadosService;
    protected $updateEmpService;

    public function __construct(EmpleadoService $empleadosService, UpdateEmpService $updateEmpService)
    {
        $this->empleadosService = $empleadosService;
        $this->updateEmpService = $updateEmpService;
    }

    public function index()
    {
        $data = $this->empleadosService->list();
        return EmpleadoResource::collection($data);
    }

    public function register(EmpleadoCreateRqt $request)
    {
        $response = $this->empleadosService->registerEmpleado($request);
        return response()->json($response);
    }

    public function update(EmpleadoUpdateRqt $request, Empleados $empleado)
    {
        $this->updateEmpService->updateEmpleado($request, $empleado);

        return response()->json([
            'success' => true,
            'message' => 'Empleado actualizado correctamente'
        ]);
    }

    public function destroy(int $empleadoId)
    {
        $this->empleadosService->deleteEmpleado($empleadoId);

        return response()->json([
            'success' => true,
            'message' => 'Empleado eliminado correctamente'
        ]);
    }


    public function restore(int $empleadoId)
    {
        $this->empleadosService->restoreEmpleado($empleadoId);

        return response()->json([
            'success' => true,
            'message' => 'Empleado restaurado correctamente'
        ]);
    }
}
