<?php

namespace App\services\Logistica\Categorias;

use App\Enums\CategoryLevel;
use App\Models\Logistica\Categorias;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class CreateCatService
{

    protected UtilsCatService $utils;

    public function __construct(UtilsCatService $utils)
    {
        $this->utils = $utils;
    }

    public function create(array $data, ?UploadedFile $file = null): array
    {
        return DB::transaction(function () use ($data, $file) {

            $this->utils->verificarSiExiste($data['name']);

            $parent = isset($data['parent_id'])
                ? $this->utils->obtenerCategoriaPadre($data['parent_id'])
                : null;

            // ✅ SIEMPRE enum
            $level = isset($data['level'])
                ? CategoryLevel::from($data['level']) // 👈 aquí SÍ
                : null;

            if (!$level) {
                if (!$parent) {
                    $level = CategoryLevel::CATEGORIA;
                } elseif ($parent->level === CategoryLevel::CATEGORIA) {
                    $level = CategoryLevel::SUBCATEGORIA;
                } elseif ($parent->level === CategoryLevel::SUBCATEGORIA) {
                    $level = CategoryLevel::ITEM;
                } else {
                    throw new BadRequestHttpException('Nivel de categoría inválido');
                }
            }

            $imageName = $file
                ? $file->store('categorias', 'public')
                : null;

            Categorias::create([
                'name' => $data['name'],
                'parent_id' => $parent?->id, // 👈 OJO aquí
                'level' => $level?->value,        // 👈 enum ✔
                'icon' => $data['icon'] ?? null,
                'is_active' => true,
                'imagen' => $imageName,
            ]);

            return [
                'success' => true,
                'message' => 'Registro exitoso',
                'detail' => 'Categoría registrada con éxito',
            ];
        });
    }

    public function addItemsToCategory(int $categoryId, array $data): array
    {
        $parentCategory = Categorias::with('children')->find($categoryId);

        if (!$parentCategory) {
            abort(404, "Categoría con ID {$categoryId} no encontrada");
        }

        $items = collect($data['items'])->map(fn($itemName) => [
            'name' => $itemName,
            'parent_category_id' => $parentCategory->id,
            'level' => CategoryLevel::ITEM->value,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        Categorias::insert($items);

        return [
            'message' => 'Se agregaron ' . count($items) . ' ítems a la categoría',
        ];
    }
}
