<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\MovInvRequest;
use App\Http\Requests\Warehouse\StoreInvRequest;
use App\services\Warehouse\InventarioService;
use App\services\Warehouse\MovInventarioservice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    protected $inventarioService;
    protected $movInv;
    public function __construct(
        InventarioService $inventarioService,
        MovInventarioservice $movInv
    ) {
        $this->inventarioService = $inventarioService;
        $this->movInv = $movInv;
    }

    public function index()
    {
        return $this->inventarioService->findAll();
    }

    public function show($id)
    {
        return $this->inventarioService->find($id);
    }

    public function store(StoreInvRequest $request)
    {
        return $this->inventarioService->createInventario(
            $request->validated()
        );
    }


    public function movimiento(MovInvRequest $request)
    {
        try {
            $inventario = DB::transaction(function () use ($request) {
                return $this->movInv->procesarMovimiento($request);
            });

            return response()->json([
                'success' => true,
                'message' => 'Movimiento realizado correctamente',
                'data' => $inventario
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
