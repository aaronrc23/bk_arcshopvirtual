<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Footer\UpdateFooterRqt;
use App\Http\Requests\Shop\Footer\UpdateFooterLinksRqt;
use App\Http\Resources\Shop\FooterResource;
use App\Models\Administracion\Empresa;
use App\services\Shop\FooterService;

class FooterController extends Controller
{
    public function __construct(
        protected FooterService $service
    ) {}

    /**
     * Obtener configuración del footer (público)
     */
    public function index()
    {
        $footer = $this->service->getFooter();

        if ($footer instanceof Empresa) {
            return new FooterResource($footer);
        }

        return response()->json($footer);
    }

    /**
     * Actualizar configuración del footer (admin)
     */
    public function update(int $id, UpdateFooterRqt $request)
    {
        return response()->json(
            $this->service->updateFooter($id, $request->validated())
        );
    }

    /**
     * Actualizar solo los links del footer (admin)
     */
    public function updateLinks(int $id, UpdateFooterLinksRqt $request)
    {
        return response()->json(
            $this->service->updateFooterLinks($id, $request->validated())
        );
    }
}
