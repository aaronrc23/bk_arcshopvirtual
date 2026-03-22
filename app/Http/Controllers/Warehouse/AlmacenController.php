<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\CreateAlmRqt;
use App\Http\Requests\Warehouse\UpdateAlmRqt;
use App\services\Warehouse\AlmacenService;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    protected $almacenService;
    public function __construct(AlmacenService $almacenService)
    {
        $this->almacenService = $almacenService;
    }

    public function index()
    {
        return $this->almacenService->findAll();
    }

    public function show($id)
    {
        return $this->almacenService->buscarAlmacenes($id);
    }

    public function store(CreateAlmRqt $request)
    {
        $this->almacenService->create($request);
        return response()->json([
            'success' => true,
            'message' => 'Registro exitoso',
            'details' => 'Almacén creado correctamente',
        ], 201);
    }

    public function update($id, UpdateAlmRqt $request)
    {
        $this->almacenService->update($id, $request);
        return response()->json([
            'success' => true,
            'message' => 'Exito',
            'details' => 'Almacén actualizado correctamente',
        ], 200);
    }

    public function destroy($id)
    {
        $this->almacenService->delete($id);
        return response()->json([
            'success' => true,
            'message' => 'Exito',
            'details' => 'Almacén eliminado correctamente',
        ], 200);
    }

    public function restore($id)
    {
        $this->almacenService->restore($id);
        return response()->json([
            'success' => true,
            'message' => 'Exito',
            'details' => 'Almacén restaurado correctamente',
        ], 200);
    }
}
