<?php

namespace App\services\Warehouse;

use App\Enums\TipoEntrada;
use App\Http\Requests\Warehouse\MovInvRequest;
use App\Http\Resources\Warehouse\MovInventarioResource;
use App\Models\Warehouse\Inventario;
use App\Models\Warehouse\MovimientoInventario;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovInventarioservice
{

    public function listMov()
    {
        return MovInventarioResource::collection(
            MovimientoInventario::with('user', 'inventario.producto', 'inventario.almacen')
                ->latest()
                ->get()
        );
    }

    public function procesarMovimiento(MovInvRequest $request)
    {
        return match ($request->tipo) {

            'TRANSFERENCIA' => $this->procesarTransferencia($request),

            default => $this->procesarMovimientoNormal($request),
        };
    }

    private function validarStock(int $stockActual, int $cantidad): int
    {
        if ($stockActual < $cantidad) {
            throw new Exception("Stock insuficiente");
        }

        return $stockActual - $cantidad;
    }

    private function calcularStock(string $tipo, int $stockActual, int $cantidad): int
    {
        return match ($tipo) {

            'ENTRADA', 'REPOSICION' => $stockActual + $cantidad,

            'SALIDA', 'VENTA' => $this->validarStock($stockActual, $cantidad),

            'AJUSTE' => $cantidad,


            default => $stockActual,
        };
    }

    private function registrarMovimiento(
        int $inventarioId,
        string $tipo,
        int $cantidad,
        int $stockAnterior,
        int $stockNuevo,
        ?string $descripcion = null
    ): void {
        MovimientoInventario::create([
            'inventario_id' => $inventarioId,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo' => $stockNuevo,
            'descripcion' => $descripcion ?? 'Movimiento inventario',
            'user_id' => Auth::id(),
        ]);
    }


    private function procesarTransferencia(MovInvRequest $request)
    {
        $cantidad = $request->cantidad;

        return DB::transaction(function () use ($request, $cantidad) {

            // 🔴 ORIGEN
            $origen = Inventario::firstOrCreate([
                'product_id' => $request->product_id,
                'almacen_id' => $request->almacen_origen_id,
            ], [
                'stock' => 0,
                'minStock' => 0,
                'estado' => true,
            ]);

            $stockOrigenAnterior = (int) $origen->stock;

            if ($stockOrigenAnterior < $cantidad) {
                throw new Exception("Stock insuficiente en almacén origen");
            }

            $stockOrigenNuevo = $stockOrigenAnterior - $cantidad;

            $origen->update([
                'stock' => $stockOrigenNuevo
            ]);

            $this->registrarMovimiento(
                inventarioId: $origen->id,
                tipo: 'TRANSFERENCIA_SALIDA',
                cantidad: $cantidad,
                stockAnterior: $stockOrigenAnterior,
                stockNuevo: $stockOrigenNuevo,
                descripcion: 'Transferencia a otro almacén'
            );

            // 🟢 DESTINO
            $destino = Inventario::firstOrCreate([
                'product_id' => $request->product_id,
                'almacen_id' => $request->almacen_destino_id,
            ], [
                'stock' => 0,
                'minStock' => 0,
                'estado' => true,
            ]);

            $stockDestinoAnterior = (int) $destino->stock;
            $stockDestinoNuevo = $stockDestinoAnterior + $cantidad;

            $destino->update([
                'stock' => $stockDestinoNuevo
            ]);

            $this->registrarMovimiento(
                inventarioId: $destino->id,
                tipo: 'TRANSFERENCIA_ENTRADA',
                cantidad: $cantidad,
                stockAnterior: $stockDestinoAnterior,
                stockNuevo: $stockDestinoNuevo,
                descripcion: 'Transferencia desde otro almacén'
            );

            return [
                'origen' => $origen->fresh(),
                'destino' => $destino->fresh(),
            ];
        });
    }


    public function procesarMovimientoNormal(MovInvRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $inventario = Inventario::firstOrCreate(
                [
                    'product_id' => $request->product_id,
                    'almacen_id' => $request->almacen_id,
                ],
                [
                    'cantidad' => 0,
                    'minStock' => 0,
                    'estado' => $request->estado ?? true,
                    'tipo' => TipoEntrada::ENTRADA->value,
                ]
            );

            $stockAnterior = (int) ($inventario->stock ?? 0);

            $stockFinal = $this->calcularStock(
                tipo: $request->tipo,
                stockActual: $stockAnterior,
                cantidad: $request->cantidad
            );

            $inventario->update([
                'stock' => $stockFinal
            ]);

            $this->registrarMovimiento(
                inventarioId: $inventario->id,
                tipo: $request->tipo,
                cantidad: $request->cantidad,
                stockAnterior: $stockAnterior,
                stockNuevo: $stockFinal,
                descripcion: $request->descripcion
            );

            return $inventario->fresh();
        });
    }
}
