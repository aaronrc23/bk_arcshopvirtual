<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Warehouse\Almacen;
use App\Models\Warehouse\Inventario;
use App\Models\Warehouse\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Total productos en inventario
        |--------------------------------------------------------------------------
        */
        $totalProductos = Inventario::count();

        /*
        |--------------------------------------------------------------------------
        | Productos con stock bajo
        | stock <= min_stock
        |--------------------------------------------------------------------------
        */
        $stockBajo = Inventario::whereColumn('stock', '<=', 'min_stock')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Número de almacenes
        |--------------------------------------------------------------------------
        */
        $numeroAlmacenes = Almacen::count();

        /*
        |--------------------------------------------------------------------------
        | Movimientos del día
        |--------------------------------------------------------------------------
        */
        $movimientosHoy = MovimientoInventario::whereDate(
            'created_at',
            Carbon::today()
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Movimientos de la semana
        |--------------------------------------------------------------------------
        */
        $movimientosSemana = MovimientoInventario::with([
            'inventario.producto',
            'inventario.almacen'
        ])
            ->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Productos con stock crítico
        |--------------------------------------------------------------------------
        */
        $productosCriticos = Inventario::with([
            'producto',
            'almacen'
        ])
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'inventario_id' => $item->id,

                    'producto' => [
                        'id' => $item->producto?->id,
                        'nombre' => $item->producto?->name,
                    ],

                    'almacen' => [
                        'id' => $item->almacen?->id,
                        'nombre' => $item->almacen?->nombre,
                    ],

                    'stock_actual' => $item->stock,
                    'stock_minimo' => $item->min_stock,
                    'estado' => $item->estado,
                ];
            });

        return response()->json([
            'success' => true,

            'data' => [

                'resumen' => [
                    'total_productos' => $totalProductos,
                    'stock_bajo' => $stockBajo,
                    'numero_almacenes' => $numeroAlmacenes,
                    'movimientos_hoy' => $movimientosHoy,
                ],

                'movimientos_semana' => $movimientosSemana->map(function ($movimiento) {
                    return [
                        'id' => $movimiento->id,
                        'tipo' => $movimiento->tipo ?? "",
                        'cantidad' => $movimiento->cantidad ?? 0,
                        'stock_anterior' => $movimiento->stock_anterior ?? 0,
                        'descripcion' => $movimiento->descripcion ?? "",
                        'fecha' => $movimiento->created_at?->format('Y-m-d H:i:s'),

                        'producto' => $movimiento->inventario?->producto?->name ?? "",
                        'almacen' => $movimiento->inventario?->almacen?->nombre ?? "",
                        'tipo_almacen' => $movimiento->inventario?->almacen?->tipo ?? "",
                    ];
                }),

                'productos_criticos' => $productosCriticos,
            ]
        ]);
    }
}
