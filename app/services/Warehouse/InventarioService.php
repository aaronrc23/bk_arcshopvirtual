<?php

namespace App\services\warehouse;

use App\Enums\TipoEntrada;
use App\Http\Requests\Warehouse\StoreInvRequest;
use App\Http\Requests\Warehouse\UpdateInvRequest;
use App\Http\Resources\Warehouse\InventarioResource;
use App\Models\Warehouse\Inventario;
use App\services\Warehouse\MovInventarioservice;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventarioService
{
    protected $movinv;
    public function __construct(MovInventarioservice $movinv)
    {
        $this->movinv = $movinv;
    }

    public function findAll()
    {
        $inventario = Inventario::with(['producto', 'almacen'])
            ->where('estado', true)
            ->get();

        return InventarioResource::collection($inventario);
    }

    public function find($id)
    {
        $inventario = Inventario::findOrFail($id);
        return response()->json($inventario);
    }


    public function createInventario(array $data)
    {
        return DB::transaction(function () use ($data) {

            $exists = Inventario::where('product_id', $data['product_id'])
                ->where('almacen_id', $data['almacen_id'])
                ->first();

            if ($exists) {
                throw new HttpResponseException(response()->json([
                    'message' => 'Ya existe inventario para este producto en ese almacén'
                ], 400));
            }

            $data['estado'] = $data['estado'] ?? true;
            $inventario = Inventario::create($data);
            return [
                'success' => true,
                'message' => 'Inventario creado correctamente',
                'data' => $inventario
            ];
        });
    }



    public function delete($id)
    {
        try {
            $inventario = Inventario::findOrFail($id);
            $inventario->delete();
            return response()->json($inventario);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el inventario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
