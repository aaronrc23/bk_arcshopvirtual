<?php

namespace App\Services\Logistica\Productos;
use DragonCode\Support\Facades\Filesystem\File;
use App\Models\Logistica\Categorias;
use App\Models\Logistica\Productos;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateServiceProducto
{
    public function __construct(
        protected UtilServiceProducto $utilService,
        protected ImgServiceProducto $imgService,
    ) {}

    /**
     * Crear producto con imágenes
     */
    public function create(array $data, ?array $files = null): array
    {
        $temporalPaths = [];

        try {
            return DB::transaction(function () use ($data, $files, &$temporalPaths) {

                $barcode = $this->utilService->generateBarcode();
                // ✅ Verificar producto duplicado
                // $this->utilService->verificarProducto(
                //     $data['codigo_barras'] ?? null,
                //     $data['codigo_interno'] ?? null
                // );

                // ✅ Obtener categoría
                $categoria = Categorias::find($data['categoria_id']);
                if (!$categoria) {
                    throw new BadRequestHttpException('La categoría no existe');
                }

                // ✅ Calcular valores (IGV / NO IGV)
                $valores = $this->utilService->calcularValores($data);
                $data['valor_unitario'] = $valores['valor_unitario'];
                $data['valor_mayoreo'] = $valores['valor_mayoreo'];

                // ✅ Crear producto
                $producto = Productos::create([
                    ...$data,
                    'destacado' => $data['destacado'] ?? false,
                    'codigo_interno' => $data['codigo_interno'] ?? '',
                ]);

                // ✅ Generar SKU si no existe
                if (empty($producto->codigo_interno)) {
                    $sku = $this->utilService->generateSKU(
                        $categoria->name ?? 'GEN',
                        $producto->id
                    );

                    $producto->update([
                        'codigo_barras' => $barcode,
                        'codigo_interno' => $sku,
                    ]);
                }

                // ---------------------------
                //  GUARDAR IMÁGENES
                // ---------------------------
                if ($files && count($files) > 0) {

                    /** @var UploadedFile $file */
                    foreach ($files as $file) {
                        $temporalPaths[] = storage_path(
                            'app/public/productimg/' . $file->hashName()
                        );
                    }

                    $this->imgService->addImages($producto, $files);
                }

                return [
                    'message' => 'Producto creado con éxito',
                ];
            });
        } catch (\Throwable $e) {

            // ❌ Limpiar archivos subidos si falla
            foreach ($temporalPaths as $path) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            throw $e;
        }
    }
}
