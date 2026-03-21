<?php

namespace App\services\Logistica\Categorias;

use App\Models\Logistica\Categorias;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UtilsCatService
{
    public function verificarSiExiste(?string $name): void
    {
        if (!$name) {
            throw new BadRequestHttpException(
                'El nombre de la categoría es requerido'
            );
        }

        if (Categorias::where('name', $name)->exists()) {
            throw new BadRequestHttpException(
                'La categoría ya existe'
            );
        }
    }

    /**
     * Buscar categoría con relaciones
     */
    public function findCategory(int $id): Categorias
    {
        $categoria = Categorias::with(['children', 'productos'])
            ->where('id', $id)
            ->where('is_active', true) // equivalente a withDeleted: false
            ->first();

        if (!$categoria) {
            throw new BadRequestHttpException(
                'La categoría no existe'
            );
        }

        return $categoria;
    }

    public function findCategoriaEliminada(int $id): Categorias
    {
        $categoria = Categorias::withTrashed()
            ->with(['children', 'productos'])
            ->where('id', $id)
            ->where('is_active', false) // equivalente a withDeleted: true
            ->first();

        if (!$categoria) {
            throw new BadRequestHttpException(
                'La categoría no existe'
            );
        }

        return $categoria;
    }

    /**
     * Valida si una categoría puede eliminarse
     */
    public function validarEliminacionCategoria(Categorias $categoria): void
    {
        if ($categoria->children()->exists()) {
            throw new BadRequestHttpException(
                'No se puede eliminar la categoría porque tiene subcategorías asociadas. Primero elimínalas o reasígnalas.'
            );
        }
    }

    /**
     * Obtiene la categoría padre
     */
    public function obtenerCategoriaPadre(?int $parentCategoryId): ?Categorias
    {
        if (!$parentCategoryId) {
            return null;
        }

        $parent = Categorias::find($parentCategoryId);

        if (!$parent) {
            throw new BadRequestHttpException(
                'La categoría padre no existe'
            );
        }

        return $parent;
    }

    /**
     * Valida que la categoría padre exista y esté activa
     */
    public function validarCategoryparent(int $id): Categorias
    {
        $category = Categorias::where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$category) {
            throw new BadRequestHttpException(
                'La categoría padre no existe'
            );
        }

        return $category;
    }

    /**
     * Validar datos para actualización
     */
    public function validarDatosActualizar(
        array $updateData,
        ?int $parentCategoryId = null
    ): void {
        if (
            empty($updateData) &&
            ($parentCategoryId === null)
        ) {
            throw new BadRequestHttpException(
                'No se han proporcionado datos para actualizar.'
            );
        }
    }
}
