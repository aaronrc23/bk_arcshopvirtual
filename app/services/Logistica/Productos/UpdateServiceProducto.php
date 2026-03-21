<?php

namespace App\Services\Logistica\Productos;

use App\Models\Logistica\ProductoImg;
use App\Models\Logistica\Productos;
use DragonCode\Support\Facades\Filesystem\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class UpdateServiceProducto
{
    protected $utilService;
    public function __construct(
        UtilServiceProducto $utilService,
    ) {
        $this->utilService = $utilService;
    }

    public function updateProductData(int $id, array $data): void
    {
        $product = Productos::find($id);
        if (!$product) {
            throw new NotFoundHttpException('Producto no encontrado');
        }
        $valores = $this->utilService->calcularValores($data);
        $product->update([
            ...$data,
            'valor_unitario' => $valores['valor_unitario'],
            'valor_mayoreo' => $valores['valor_mayoreo'],
        ]);
    }

    /**
     * Actualizar imágenes de un producto
     */
    public function updateProductImages(
        int $productId,
        array $imagenes,
        array $newFiles
    ): void {
        $product = Productos::with('imagenes')->find($productId);

        if (!$product) {
            throw new NotFoundHttpException('Producto no encontrado');
        }

        $maxImages = 5;

        // --------------------------------
        // 1️⃣ ELIMINAR IMÁGENES REMOVIDAS
        // --------------------------------
        $idsEnviados = collect($imagenes)
            ->filter(fn($img) => isset($img['id']))
            ->pluck('id')
            ->toArray();

        $aEliminar = $product->imagenes->filter(
            fn($img) => !in_array($img->id, $idsEnviados)
        );

        foreach ($aEliminar as $img) {
            $fullPath = public_path($img->url);

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }

            $img->delete();
        }

        // --------------------------------
        // 2️⃣ ACTUALIZAR ORDEN / PRINCIPAL
        // --------------------------------
        foreach ($imagenes as $imgData) {
            if (!empty($imgData['id'])) {
                ProductoImg::where('id', $imgData['id'])->update([
                    'orden' => $imgData['orden'],
                    'is_principal' => $imgData['isPrincipal'] ?? false,
                ]);
            }
        }

        // --------------------------------
        // 3️⃣ AÑADIR NUEVAS IMÁGENES
        // --------------------------------
        $existentes = collect($imagenes)->whereNotNull('id')->count();
        $disponibles = $maxImages - $existentes;

        $filesAllowed = array_slice($newFiles, 0, $disponibles);

        foreach ($filesAllowed as $file) {
            $path = $file->store('productimg', 'public');
            // buscar slot libre
            $slot = collect($imagenes)->first(
                fn($img) => empty($img['id']) && empty($img['_assigned'])
            );

            if (!$slot) {
                break;
            }



            ProductoImg::create([
                'url' => $path,
                'orden' => $slot['orden'],
                'is_principal' => $slot['isPrincipal'] ?? false,
                'producto_id' => $productId,
            ]);

            $slot['_assigned'] = true;
        }
    }

    /**
     * Eliminar una imagen individual
     */
    public function deleteImage(int $imgId): void
    {
        $image = ProductoImg::find($imgId);

        if (!$image) {
            throw new NotFoundHttpException('Imagen no encontrada');
        }

        $fullPath = public_path($image->url);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }

        $image->delete();
    }



    //**----Desactivar Producto -----*/
    public function desactivar(int $id)
    {
        $producto = Productos::find($id);
        if ($producto->activo == false) {
            abort(400, 'Producto ya se encuentra desactivado');
        }
        $producto->update([
            'activo' => false,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Producto desactivado',
            'detail' => "El producto {$producto->name} se ha desactivado correctamente",
        ]);
    }

    /*---Reactivar Producto -----*/
    public function reactivar(int $id)
    {
        $producto = Productos::find($id);

        $producto->update([
            'activo' => true,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Producto reactivado',
            'detail' => "El producto {$producto->name} se ha reactivado correctamente",
        ]);
    }

    /*---Borrado logico--- */
    public function deleteProducto(int $productId): void
    {
        $product = Productos::findOrFail($productId);

        $product->delete();
    }
}
