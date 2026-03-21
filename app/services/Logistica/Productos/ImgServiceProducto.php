<?php

namespace App\Services\Logistica\Productos;

use App\Models\Logistica\ProductoImg;
use App\Models\Logistica\Productos;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ImgServiceProducto
{
    /**
     * Agregar imágenes a un producto
     */
    public function addImages(Productos $product, array $images): Collection
    {
        $existingImages = ProductoImg::where('producto_id', $product->id)
            ->orderBy('orden')
            ->get();

        $hasPrincipal = $existingImages->contains(fn($img) => $img->is_principal);
        $nextOrder = $existingImages->count() + 1;

        $newImages = collect();

        foreach ($images as $index => $file) {

            // 🔥 GUARDAR ARCHIVO FÍSICAMENTE
            $path = $file->store('productimg', 'public');

            $newImages->push(
                ProductoImg::create([
                    'url' => $path,
                    'producto_id' => $product->id,
                    'orden' => $nextOrder,
                    'is_principal' => !$hasPrincipal && $index === 0,
                ])
            );

            $nextOrder++;
        }

        return $newImages;
    }

    /**
     * Listar imágenes por producto (modelo)
     */
    public function listImages(Productos $product): Collection
    {
        return ProductoImg::where('producto_id', $product->id)
            ->orderBy('orden')
            ->get();
    }

    /**
     * Listar imágenes por ID del producto
     */
    public function listImagesById(int $productId): Collection
    {
        return ProductoImg::where('producto_id', $productId)
            ->orderBy('orden')
            ->get();
    }

    /**** Update isPrincipal */
    public function updateIsPrincipal(int $id)
    {
        return DB::transaction(function () use ($id) {

            $img = ProductoImg::find($id);

            if (!$img) {
                throw new BadRequestHttpException('Imagen no encontrada');
            }

            // 🔴 quitar principal a TODAS las del producto
            ProductoImg::where('producto_id', $img->producto_id)
                ->update(['is_principal' => false]);

            // 🟢 poner esta como principal
            $img->is_principal = true;
            $img->save();

            return $img->fresh();
        });
    }
}
