<?php

namespace App\services\Warehouse;

use App\Enums\TipoEntrada;
use App\Http\Requests\Warehouse\MovInvRequest;
use App\Models\Warehouse\Inventario;
use App\Models\Warehouse\MovimientoInventario;
use Exception;
use Illuminate\Support\Facades\Auth;

class MovInventarioservice
{


    //codigo de movimiento reutilizable
    private function movInventario($data)
    {
        $movimiento = MovimientoInventario::create([
            'inventario_id' => $data['inventario_id'],
            'tipo' => $data['tipo'],
            'cantidad' => $data['cantidad'],
            'stock_anterior' => $data['stock_anterior'],
            'descripcion' => $data['descripcion'],
            'user_id' => $data['user_id'],
        ]);
        return $movimiento;
    }


    public function procesarMovimiento(
        MovInvRequest $request
    ) {

        // buscar inventario o crearlo
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

        $stockAnterior = $inventario->cantidad;
        $stockFinal = $stockAnterior;

        switch ($request->tipo) {

            case 'ENTRADA':
            case 'REPOSICION':
                $stockFinal += $request->cantidad;
                break;

            case 'SALIDA':
            case 'VENTA':
                if ($stockAnterior < $request->cantidad) {
                    throw new Exception("Stock insuficiente");
                }
                $stockFinal -= $request->cantidad;
                break;

            case 'AJUSTE':
                $stockFinal = $request->cantidad;
                break;
        }

        // actualizar stock
        $inventario->update([
            'cantidad' => $stockFinal
        ]);

        // registrar movimiento
        $this->movInventario([
            'inventario_id' => $inventario->id,
            'tipo' => $request->tipo,
            'cantidad' => $request->cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo' => $stockFinal,
            'descripcion' => $request->descripcion ?? 'Movimiento inventario',
            'user_id' => Auth::id(),
        ]);

        return $inventario->fresh();
    }
}
