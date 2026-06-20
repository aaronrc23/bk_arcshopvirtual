<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Banner\CreateBannerRqt;
use App\Http\Resources\Shop\BannerResource;
use App\Models\Shop\Banner;
use App\services\Shop\BannerService;

class BannerController extends Controller
{
    public function __construct(
        protected BannerService $service
    ) {}

    /**
     * Listar todos los banners (panel admin)
     */
    public function index()
    {
        return response()->json(
            $this->service->list()
        );
    }

    /**
     * Listar banners activos (frontend público)
     */
    public function active()
    {
        return BannerResource::collection(
            Banner::activos()->get()
        );
    }

    /**
     * Obtener un banner por ID
     */
    public function show(int $id)
    {
        $banner = Banner::withTrashed()->findOrFail($id);
        return new BannerResource($banner);
    }

    /**
     * Crear banner
     */
    public function store(CreateBannerRqt $request)
    {
        return response()->json(
            $this->service->create(
                $request->validated(),
                $request->file('imagen')
            ),
            201
        );
    }

    /**
     * Actualizar banner
     */
    public function update(int $id, CreateBannerRqt $request)
    {
        return response()->json(
            $this->service->update(
                $id,
                $request->validated(),
                $request->file('imagen')
            )
        );
    }

    /**
     * Desactivar banner
     */
    public function desactivar(int $id)
    {
        return response()->json(
            $this->service->desactivar($id)
        );
    }

    /**
     * Reactivar banner
     */
    public function reactivar(int $id)
    {
        return response()->json(
            $this->service->reactivar($id)
        );
    }

    /**
     * Eliminar banner (soft delete)
     */
    public function destroy(int $id)
    {
        return response()->json(
            $this->service->delete($id)
        );
    }

    /**
     * Restaurar banner eliminado
     */
    public function restore(int $id)
    {
        return response()->json(
            $this->service->restore($id)
        );
    }
}
