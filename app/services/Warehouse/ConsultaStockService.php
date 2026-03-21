<?php

namespace App\services\Warehouse;

use App\Http\Requests\Warehouse\StoreInvRequest;
use App\Models\Warehouse\Inventario;
use App\Repositories\warehouse\InventarioRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class ConsultaStockService
{
    public function __construct(
        private InventarioRepository $repo
    ) {}

    public function crearDetalle(StoreInvRequest $dto)
    {
        return $this->repo->create($dto->validated());
    }

    public function listarVirtuales()
    {
        $data = $this->repo->listarVirtuales();

        if ($data->isEmpty()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'No hay productos en almacenes virtuales.'
                ], 404)
            );
        }

        return $data;
    }

    public function listarPorAlmacen(int $almacenId)
    {
        if (!$almacenId) {
            throw new HttpResponseException(
                response()->json(['message' => 'ID de almacén inválido'], 400)
            );
        }

        return $this->repo->listarPorAlmacen($almacenId);
    }

    public function buscarPorNombre(string $nombre, int $almacenId)
    {
        $data = $this->repo->buscarPorNombre($nombre, $almacenId);

        if ($data->isEmpty()) {
            throw new HttpResponseException(
                response()->json([
                    'message' =>
                    "No se encontró el producto {$nombre} en el almacén {$almacenId}"
                ], 400)
            );
        }

        return $data;
    }

    public function findByDto(array $dto): ?Inventario
    {
        if (empty($dto['productId']) || empty($dto['almacenId'])) {
            return null;
        }

        return Inventario::where('product_id', $dto['productId'])
            ->where('almacen_id', $dto['almacenId'])
            ->first();
    }


    public function updateStock(Inventario $detalle, int $cantidad): Inventario
    {
        DB::transaction(function () use ($detalle, $cantidad) {
            $detalle->increment('stock', $cantidad);
        });

        return $detalle->fresh();
    }
    public function actualizarStock($inventario, int $cantidad)
    {
        $inventario->increment('stock', $cantidad);
        return $inventario->refresh();
    }

    public function buscarAlmprod(int $prodId, int $almacenId)
    {
        $detalle = Inventario::with(['product', 'almacen'])
            ->where('product_id', $prodId)
            ->where('almacen_id', $almacenId)
            ->get();

        if ($detalle->isEmpty()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => "No se encontró el producto con ID: {$prodId} en el almacén con ID: {$almacenId}"
                ], 400)
            );
        }

        return $detalle;
    }
}
