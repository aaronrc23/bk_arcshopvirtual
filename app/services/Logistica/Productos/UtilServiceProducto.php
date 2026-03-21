<?php

namespace App\Services\Logistica\Productos;

use App\Models\Logistica\ProductoImg;
use App\Models\Logistica\Productos;
use Illuminate\Validation\ValidationException;

class UtilServiceProducto
{

    public function generateBarcode(): string
    {
        do {
            $barcode = str_pad(
                random_int(0, 9999999999999),
                13,
                '0',
                STR_PAD_LEFT
            );
        } while (Productos::where('codigo_barras', $barcode)->exists());
        return $barcode;
    }

    /**
     * Generar SKU basado en categoría + ID
     */
    public function generateSKU(string $categoryName, int $productId): string
    {
        $prefix = strtoupper(substr($categoryName, 0, 3));
        $idPadded = str_pad((string) $productId, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$idPadded}";
    }

    /**
     * Verificar si existe producto por código de barras o interno
     */
    public function verificarProducto(?string $codigoBarras = null, ?string $codigoInterno = null): void
    {
        if (!$codigoBarras && !$codigoInterno) {
            return;
        }

        $exists = Productos::query()
            ->when(
                $codigoBarras,
                fn($q) =>
                $q->orWhere('codigo_barras', $codigoBarras)
            )
            ->when(
                $codigoInterno,
                fn($q) =>
                $q->orWhere('codigo_interno', $codigoInterno)
            )
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'producto' => 'El producto ya está registrado (código de barras o código interno duplicado).'
            ]);
        }
    }

    /**
     * Calcular valores al crear producto
     */
    public function calcularValores(array $data): array
    {
        $precioVenta = (float) ($data['precio_venta'] ?? 0);
        $precioMayoreo = (float) ($data['precio_mayoreo'] ?? 0);

        if ($precioVenta <= 0 || $precioMayoreo <= 0) {
            throw ValidationException::withMessages([
                'precio' => 'Los valores de precio_venta y precio_mayoreo deben ser números válidos.'
            ]);
        }

        // No afecto IGV
        if (($data['tipo_afectacion_id'] ?? null) !== '10') {
            return [
                'valor_unitario' => $precioVenta,
                'valor_mayoreo' => $precioMayoreo,
            ];
        }

        // Afecto IGV (dividir entre 1.18)
        return [
            'valor_unitario' => round($precioVenta / 1.18, 6),
            'valor_mayoreo' => round($precioMayoreo / 1.18, 6),
        ];
    }

    /**
     * Calcular valores al actualizar producto
     */
    public function calcularValoresUpdate(array $data): array
    {
        $precioVenta = (float) ($data['precio_venta'] ?? 0);
        $precioMayoreo = (float) ($data['precio_mayoreo'] ?? 0);

        if ($precioVenta <= 0 || $precioMayoreo <= 0) {
            throw ValidationException::withMessages([
                'precio' => 'Los valores de precio_venta y precio_mayoreo deben ser números válidos.'
            ]);
        }

        if (($data['tipo_afectacion_id'] ?? null) !== '10') {
            return [
                'valor_unitario' => $precioVenta,
                'valor_mayoreo' => $precioMayoreo,
            ];
        }

        return [
            'valor_unitario' => round($precioVenta / 1.18, 6),
            'valor_mayoreo' => round($precioMayoreo / 1.18, 6),
        ];
    }


    public function addImages(Productos $producto, array $files): void
    {
        foreach ($files as $index => $file) {

            $path = $file->store('productimg', 'public');

            ProductoImg::create([
                'producto_id' => $producto->id,
                'url' => $path,
                'orden' => $index + 1,
                'is_principal' => $index === 0,
            ]);
        }
    }
}
