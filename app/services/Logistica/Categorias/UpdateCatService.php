<?php

namespace App\Services\Logistica\Categorias;

use App\Enums\CategoryLevel;
use App\Models\Logistica\Categorias;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateCatService
{
    public function __construct(
        protected UtilsCatService $utils
    ) {}

    /**
     * Actualizar categoría
     */
    public function update(
        int $id,
        array $data,
        ?UploadedFile $file = null
    ): array {
        return DB::transaction(function () use ($id, $data, $file) {

            $categoria = $this->utils->findCategory($id);

            $parentCategoryId = $data['parent_id'] ?? null;
            unset($data['parent_id']);

            // ✅ Validar datos
            $this->utils->validarDatosActualizar($data, $parentCategoryId);

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Manejo de imagen
            |--------------------------------------------------------------------------
            */
            $imagenFinal = $categoria->imagen;

            // ➕ Reemplazar imagen
            if ($file) {
                $imagenFinal = $file->store('categorias', 'public');

                if ($categoria->imagen) {
                    Storage::disk('public')->delete($categoria->imagen);
                }
            }

            // ❌ Eliminar imagen
            if (!empty($data['removeImage']) && $data['removeImage'] === true) {
                if ($categoria->imagen) {
                    Storage::disk('public')->delete($categoria->imagen);
                }

                $imagenFinal = null;
            }

            unset($data['removeImage']);

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Actualizar categoría
            |--------------------------------------------------------------------------
            */
            $categoria->update([
                ...$data,
                'imagen' => $imagenFinal,
                'parent_id' => $parentCategoryId
                    ? $this->utils->validarCategoryparent($parentCategoryId)->id
                    : $categoria->parent_id,
            ]);


            return [
                'success' => true,
                'message' => 'Actualizado',
                'detail' => "La categoría {$categoria->name} se ha actualizado correctamente",
            ];
        });
    }



    public function desactivar(int $id)
    {
        $categoria = Categorias::findOrFail($id);

        // 🔎 Verificar si tiene subcategorías activas
        $tieneHijosActivos = $categoria->children()
            ->where('is_active', true)
            ->exists();

        if ($tieneHijosActivos) {
            abort(422, 'La categoría tiene subcategorías activas');
        }

        // ✅ Ahora sí, desactivamos
        $categoria->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Categoría desactivada',
            'detail' => "La categoría {$categoria->name} se ha desactivado correctamente",
        ]);
    }

    public function reactivar(int $id)
    {
        $categoria = Categorias::findOrFail($id);
        $categoria->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Categoría reactivada',
            'detail' => "La categoría {$categoria->name} se ha reactivado correctamente",
        ]);
    }

    /**
     * Eliminar categoría (borrado lógico)
     */
    public function remove(int $id): array
    {
        return DB::transaction(function () use ($id) {

            $categoria = $this->utils->findCategory($id);

            // 1️⃣ Validar subcategorías
            $this->utils->validarEliminacionCategoria($categoria);

            // 2️⃣ Validar productos asociados
            if ($categoria->productos()->exists()) {
                throw new BadRequestHttpException(
                    'No se puede eliminar esta categoría porque tiene productos asociados.'
                );
            }

            // 3️⃣ Eliminar imagen física
            if ($categoria->imagen) {
                Storage::disk('public')->delete($categoria->imagen);
            }

            // 4️⃣ Borrado lógico
            $categoria->update([
                'is_active' => false,
                'imagen' => null,
            ]);

            $categoria->delete(); // SoftDelete

            return [
                'success' => true,
                'message' => 'Eliminado',
                'detail' => "La categoría {$categoria->name} se ha eliminado correctamente",
            ];
        });
    }


    /*Restaurar  categoria*/
    public function restore(int $id): array
    {
        return DB::transaction(function () use ($id) {
            $categoria = $this->utils->findCategoriaEliminada($id);

            $categoria->update([
                'is_active' => true,
                'imagen' => $categoria->imagen,
            ]);

            $categoria->restore(); // SoftDelete

            return [
                'success' => true,
                'message' => 'Restaurado',
                'detail' => "La categoría {$categoria->name} se ha restaurado correctamente",
            ];
        });
    }
}
