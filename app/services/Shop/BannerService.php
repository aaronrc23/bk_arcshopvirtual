<?php

namespace App\services\Shop;

use App\Models\Shop\Banner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BannerService
{
    /**
     * Listar todos los banners ordenados
     */
    public function list()
    {
        return Banner::query()
            ->withTrashed()
            ->orderBy('orden')
            ->orderBy('id')
            ->get();
    }

    /**
     * Listar solo banners activos (para el frontend público)
     */
    public function listActive()
    {
        return Banner::activos()->get();
    }

    /**
     * Crear banner con imagen optimizada a WebP
     */
    public function create(array $data, ?UploadedFile $file = null): array
    {
        return DB::transaction(function () use ($data, $file) {

            $imagePath = $file
                ? $this->optimizeAndStore($file, 'banners')
                : null;

            Banner::create([
                'titulo' => $data['titulo'] ?? null,
                'subtitulo' => $data['subtitulo'] ?? null,
                'enlace' => $data['enlace'] ?? null,
                'orden' => $data['orden'] ?? 0,
                'is_active' => true,
                'url_imagen' => $imagePath,
            ]);

            return [
                'success' => true,
                'message' => 'Registro exitoso',
                'detail' => 'Banner creado con éxito',
            ];
        });
    }

    /**
     * Actualizar banner
     */
    public function update(int $id, array $data, ?UploadedFile $file = null): array
    {
        return DB::transaction(function () use ($id, $data, $file) {

            $banner = Banner::withTrashed()->findOrFail($id);

            // Si viene nueva imagen, eliminar la anterior y guardar la nueva
            if ($file) {
                if ($banner->getRawOriginal('url_imagen')) {
                    Storage::disk('public')
                        ->delete($banner->getRawOriginal('url_imagen'));
                }

                $imagePath = $this->optimizeAndStore($file, 'banners');
                $data['url_imagen'] = $imagePath;
            }

            $banner->update([
                'titulo' => $data['titulo'] ?? $banner->titulo,
                'subtitulo' => $data['subtitulo'] ?? $banner->subtitulo,
                'enlace' => $data['enlace'] ?? $banner->enlace,
                'orden' => $data['orden'] ?? $banner->orden,
                'url_imagen' => $data['url_imagen'] ?? $banner->getRawOriginal('url_imagen'),
            ]);

            return [
                'success' => true,
                'message' => 'Actualización exitosa',
                'detail' => 'Banner actualizado con éxito',
            ];
        });
    }

    /**
     * Desactivar banner
     */
    public function desactivar(int $id): array
    {
        return DB::transaction(function () use ($id) {
            $banner = Banner::findOrFail($id);
            $banner->update(['is_active' => false]);

            return [
                'success' => true,
                'message' => 'Banner desactivado',
                'detail' => 'El banner ha sido desactivado exitosamente',
            ];
        });
    }

    /**
     * Reactivar banner
     */
    public function reactivar(int $id): array
    {
        return DB::transaction(function () use ($id) {
            $banner = Banner::withTrashed()->findOrFail($id);
            $banner->update(['is_active' => true]);

            return [
                'success' => true,
                'message' => 'Banner reactivado',
                'detail' => 'El banner ha sido reactivado exitosamente',
            ];
        });
    }

    /**
     * Eliminar banner (soft delete)
     */
    public function delete(int $id): array
    {
        return DB::transaction(function () use ($id) {
            $banner = Banner::findOrFail($id);

            // Eliminar imagen física
            if ($banner->getRawOriginal('url_imagen')) {
                Storage::disk('public')
                    ->delete($banner->getRawOriginal('url_imagen'));
            }

            $banner->delete();

            return [
                'success' => true,
                'message' => 'Eliminación exitosa',
                'detail' => 'Banner eliminado con éxito',
            ];
        });
    }

    /**
     * Restaurar banner eliminado
     */
    public function restore(int $id): array
    {
        return DB::transaction(function () use ($id) {
            $banner = Banner::withTrashed()->findOrFail($id);
            $banner->restore();

            return [
                'success' => true,
                'message' => 'Restauración exitosa',
                'detail' => 'Banner restaurado con éxito',
            ];
        });
    }

    /**
     * 🔥 Optimizar imagen: redimensionar y convertir a WebP con calidad 80%
     * Usa GD library (built-in en PHP, sin dependencias externas)
     */
    private function optimizeAndStore(UploadedFile $file, string $folder): string
    {
        // Obtener la imagen desde el archivo temporal
        $sourcePath = $file->getPathname();
        $mimeType = $file->getMimeType();

        // Crear recurso GD según el tipo de imagen
        $image = match ($mimeType) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($sourcePath),
            'image/png' => @imagecreatefrompng($sourcePath),
            'image/webp' => @imagecreatefromwebp($sourcePath),
            'image/gif' => @imagecreatefromgif($sourcePath),
            default => throw new BadRequestHttpException('Formato de imagen no soportado'),
        };

        if (!$image) {
            throw new BadRequestHttpException('No se pudo procesar la imagen');
        }

        // 🔥 Dimensiones recomendadas para banners: 1600×800px (relación 2:1)
        $origWidth = imagesx($image);
        $origHeight = imagesy($image);
        $maxWidth = 1600;
        $maxHeight = 800;

        // Redimensionar si excede el ancho máximo
        if ($origWidth > $maxWidth) {
            $ratio = $maxWidth / $origWidth;
            $newWidth = $maxWidth;
            $newHeight = (int) ($origHeight * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Preservar transparencia PNG
            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($image);
            $image = $resized;

            // Actualizar dimensiones originales después del redimensionado
            $origWidth = $newWidth;
            $origHeight = $newHeight;
        }

        // Redimensionar si excede el alto máximo
        if ($origHeight > $maxHeight) {
            $ratio = $maxHeight / $origHeight;
            $newWidth = (int) ($origWidth * $ratio);
            $newHeight = $maxHeight;

            $resized = imagecreatetruecolor($newWidth, $newHeight);

            imagealphablending($resized, false);
            imagesavealpha($resized, true);

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($image);
            $image = $resized;
        }

        // 🔥 Guardar como WebP con calidad 80 (balance calidad/peso)
        $filename = uniqid('banner_') . '.webp';
        $storagePath = "{$folder}/{$filename}";
        $fullPath = Storage::disk('public')->path($storagePath);

        // Asegurar que el directorio existe
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        imagewebp($image, $fullPath, 80);
        imagedestroy($image);

        return $storagePath;
    }
}
