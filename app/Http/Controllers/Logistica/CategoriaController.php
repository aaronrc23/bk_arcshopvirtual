<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;
use App\Http\Requests\Logistica\Categorias\CreateCatRqt;
use App\Http\Requests\Logistica\Categorias\CreateitemsCatRqt;
use App\Http\Requests\Logistica\Categorias\UpdateCategoriaRqt;
use App\Http\Resources\Logistica\Categorias\CatDropresource;
use App\Http\Resources\Logistica\Categorias\CatFullresource;
use App\Http\Resources\Logistica\Categorias\CatPadreresource;
use App\Services\Logistica\Categorias\CreateCatService;
use App\Services\Logistica\Categorias\ListCatService;
use App\Services\Logistica\Categorias\UpdateCatService;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    protected $listcat;
    protected $catService;
    protected $updateService;

    public function __construct(ListCatService $listcat, CreateCatService $catservice, UpdateCatService $updateService)
    {
        $this->listcat = $listcat;
        $this->catService = $catservice;
        $this->updateService = $updateService;
    }

    public function listCategoriasFull()
    {
        return CatFullresource::collection($this->listcat->listCategoriasFull());
    }

    public function listCatPadre()
    {
        return CatPadreresource::collection($this->listcat->listCategoriasPadre());
    }

    public function listCatEstructuraFull()
    {
        return $this->listcat->getCategoriasEstructura();
    }

    public function listCatOne(int $id)
    {
        return $this->listcat->findOne($id);
    }

    public function listCatHijas()
    {
        return $this->listcat->categoriasHijas();
    }

    public function catEliminadas()
    {
        return CatDropresource::collection($this->listcat->categoriasEliminadas());
    }


    public function create(CreateCatRqt $request)
    {
        return response()->json(
            $this->catService->create(
                $request->validated(),
                $request->file('imagen')
            )
        );
    }


    public function addItems(
        int $categoryId,
        CreateitemsCatRqt $request,
    ) {
        return response()->json(
            $this->catService->addItemsToCategory(
                $categoryId,
                $request->validated()
            )
        );
    }
    public function update(
        int $id,
        UpdateCategoriaRqt $request,

    ) {
        return response()->json(
            $this->updateService->update(
                $id,
                $request->only([
                    'name',
                    'level',
                    'icon',
                    'parent_id',
                    'removeImage'
                ]),
                $request->file('imagen')
            )
        );
    }

    public function desactivar(int $id)
    {
        return $this->updateService->desactivar($id);
    }

    public function reactivar(int $id)
    {
        return $this->updateService->reactivar($id);
    }

    public function destroy(
        int $id,

    ) {
        return response()->json(
            $this->updateService->remove($id)
        );
    }

    public function restore(int $id)
    {
        return response()->json(
            $this->updateService->restore($id)
        );
    }
}
