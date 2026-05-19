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
            ->get()
            ->groupBy('product_id')
            ->map(function ($items) {

                $producto = $items->first()->producto;

                return [
                    'product_id' => $producto->id,
                    'producto' => $producto->name,
                    'codigo' => $producto->codigo_interno ?? null,
                    'stock_total' => $items->sum('stock'),
                    'min_stock' => $items->sum('min_stock'),
                    'max_stock' => $items->sum('max_stock'),

                    // cantidad de almacenes
                    'almacenes_count' => $items->count(),

                    // detalle para desplegable
                    'almacenes' => $items->map(function ($inventario) {
                        return [
                            'inventario_id' => $inventario->id,
                            'almacen_id' => $inventario->almacen_id,
                            'almacen' => $inventario->almacen->nombre,
                            'stock' => $inventario->stock,
                            'min_stock' => $inventario->min_stock,
                            'max_stock' => $inventario->max_stock,
                            'tipo' => $inventario->almacen->tipo ?? "-",
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json($inventario);
    }

    public function find($id)
    {
        $inventario = Inventario::find($id);
        if (!$inventario) {
            return response()->json(['error' => 'Inventario no encontrado'], 404);
        }
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
            $inventario = Inventario::find($id);
            if (!$inventario) {
                return response()->json(['error' => 'Inventario no encontrado'], 404);
            }
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
