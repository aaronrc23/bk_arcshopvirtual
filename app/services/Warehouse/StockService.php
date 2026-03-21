<?php

namespace App\Services\Warehouse;


use App\Models\Warehouse\Inventario;
use App\Services\ValidationStockService;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct(
        private ValidationStockService $validationService,
        private ConsultaStockService $consultaStockService,
        private AlmacenService $almacenService,
    ) {}

    /**
     * 🔻 Reducir stock
     */
    public function reduceStock(array $dto): Inventario
    {
        $this->validationService->validateIds(
            $dto['productId'],
            $dto['almacenId']
        );

        $detalle = $this->consultaStockService->findByDto([
            'productId' => $dto['productId'],
            'almacenId' => $dto['almacenId'],
        ]);

        if (!$detalle) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'No existe detalle para ese producto y almacén'
                ], 400)
            );
        }

        if ($detalle->stock < $dto['cantidad']) {
            throw new HttpResponseException(
                response()->json(['message' => 'Stock insuficiente'], 400)
            );
        }

        return $this->consultaStockService
            ->updateStock($detalle, -$dto['cantidad']);
    }

    /**
     * 🔁 Transferir stock entre almacenes
     */
    public function transferirStock(array $dto): array
    {
        return DB::transaction(function () use ($dto) {

            $required = ['productId', 'almacenOrigenId', 'almacenDestinoId', 'cantidad'];

            foreach ($required as $field) {
                if (empty($dto[$field])) {
                    throw new HttpResponseException(
                        response()->json([
                            'message' => 'Todos los campos son obligatorios'
                        ], 400)
                    );
                }
            }

            $this->almacenService->buscarAlmacenes($dto['almacenDestinoId']);

            if ($dto['almacenOrigenId'] === $dto['almacenDestinoId']) {
                throw new HttpResponseException(
                    response()->json([
                        'message' =>
                        'El almacén origen y destino no pueden ser iguales'
                    ], 400)
                );
            }

            // Validar stock en almacén origen
            $detalleOrigen = $this->consultaStockService
                ->buscarAlmprod($dto['productId'], $dto['almacenOrigenId']);

            if ($detalleOrigen->first()->stock < $dto['cantidad']) {
                throw new HttpResponseException(
                    response()->json([
                        'message' =>
                        'Stock insuficiente en el almacén origen'
                    ], 400)
                );
            }

            // 1️⃣ Reducir stock origen
            $this->reduceStock([
                'productId' => $dto['productId'],
                'almacenId' => $dto['almacenOrigenId'],
                'cantidad' => $dto['cantidad'],
            ]);

            // 2️⃣ Sumar stock destino
            $this->findOrCreateAndUpdateStock([
                'productId' => $dto['productId'],
                'almacenId' => $dto['almacenDestinoId'],
                'stock' => $dto['cantidad'],
                'minStock' => 0,
                'estado' => true,
            ]);

            return ['mensaje' => 'Traspaso realizado correctamente'];
        });
    }

    /**
     * 🔍 Buscar o crear inventario y actualizar stock
     */
    public function findOrCreateAndUpdateStock(array $dto): Inventario
    {
        $this->validationService->validateIds(
            $dto['productId'],
            $dto['almacenId']
        );

        $detalle = $this->consultaStockService->findByDto([
            'productId' => $dto['productId'],
            'almacenId' => $dto['almacenId'],
        ]);

        if ($detalle) {
            return $this->consultaStockService
                ->updateStock($detalle, $dto['stock'] ?? 0);
        }

        return $this->consultaStockService->crearDetalle($dto);
    }
}
