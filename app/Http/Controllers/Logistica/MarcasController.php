<?php

namespace App\Http\Controllers\Logistica;

use App\Http\Controllers\Controller;
use App\services\Logistica\Marcas\MarcaService;
use Illuminate\Http\Request;

class MarcasController extends Controller
{
    public function __construct(
        protected MarcaService $service
    ) {}

    public function index()
    {
        return response()->json(
            $this->service->list()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'slug' => 'required|string|max:150',
            'imagen' => 'nullable|image|max:2048'
        ]);

        return response()->json(
            $this->service->create(
                $request->all(),
                $request->file('imagen')
            )
        );
    }
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'imagen' => 'nullable|image|max:2048'
        ]);

        return response()->json(
            $this->service->update(
                $id,
                $request->all(),
                $request->file('imagen')
            )
        );
    }


    public function desactivar(int $id)
    {
        return response()->json(
            $this->service->desactivar($id)
        );
    }

    public function reactivar(int $id)
    {
        return response()->json(
            $this->service->reactivar($id)
        );
    }

    public function destroy(int $id)
    {
        return response()->json(
            $this->service->delete($id)
        );
    }

    //borrado logico
    public function restore(int $id)
    {
        return response()->json(
            $this->service->restore($id)
        );
    }
    public function forceDelete(int $id)
    {
        return response()->json(
            $this->service->forceDelete($id)
        );
    }
}
