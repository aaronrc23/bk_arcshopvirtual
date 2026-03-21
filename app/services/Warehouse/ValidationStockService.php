<?php

namespace App\services;


use App\Services\Warehouse\AlmacenService;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidationStockService
{
    public function __construct(
        private AlmacenService $almacenService
    ) {}

    /**
     * ✅ Valida IDs obligatorios
     */
    public function validateIds(?int $productId, ?int $almacenId): void
    {
        if (!$productId || !$almacenId) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Product ID and Almacen ID are required.'
                ], 400)
            );
        }
    }

    /**
     * 🏬 Verifica que existan almacenes registrados
     */
    public function validarAlmacenes(int $almacenId)
    {
        $almacenes = $this->almacenService->buscarAlmacenes($almacenId);

        if ($almacenes->isEmpty()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'No se encontraron almacenes registrados'
                ], 400)
            );
        }

        return $almacenes;
    }

    /**
     * 🔁 Valida DTO de transferencia
     */
    public function validateTransferDto(array $dto): void
    {
        $required = [
            'productId',
            'almacenOrigenId',
            'almacenDestinoId',
            'cantidad',
        ];

        foreach ($required as $field) {
            if (empty($dto[$field])) {
                throw new HttpResponseException(
                    response()->json([
                        'message' => 'Todos los campos son obligatorios'
                    ], 400)
                );
            }
        }

        if ($dto['almacenOrigenId'] === $dto['almacenDestinoId']) {
            throw new HttpResponseException(
                response()->json([
                    'message' =>
                    'El almacén origen y destino no pueden ser iguales'
                ], 400)
            );
        }
    }
}
