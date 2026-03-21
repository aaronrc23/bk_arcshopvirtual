<?php

namespace App\services\Warehouse;

use App\Enums\TipoAlm;
use App\Http\Requests\Warehouse\CreateAlmRqt;
use App\Http\Requests\Warehouse\UpdateAlmRqt;
use App\Models\Warehouse\Almacen;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlmacenService
{
    private function generarCodigo()
    {
        $last = Almacen::whereNotNull('code')
            ->orderBy('id', 'desc')
            ->first();

        if (!$last) {
            return 'ALM-001';
        }

        $lastNumber = (int) str_replace('ALM-', '', $last->code);
        $newNumber = $lastNumber + 1;

        return 'ALM-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Busca un almacén por nombre y devuelve su información.
     */
    public function findByName(string $name): ?Almacen
    {
        return Almacen::where('name', $name)->first();
    }

    /**
     * Verifica si existe un almacén principal registrado.
     *
     * @throws NotFoundHttpException
     */
    public function existsPrincipal(): Almacen
    {
        $principal = Almacen::where('isPrincipal', true)->first();

        if (!$principal) {
            throw new NotFoundHttpException('No hay un almacén principal definido');
        }

        return $principal;
    }

    /**
     * Lista todos los almacenes no eliminados (soft delete).
     */
    public function findAll(): Collection
    {
        return Almacen::query()->get();
    }

    public function buscarAlmacenes($id)
    {
        $almacen = Almacen::findOrFail($id);
        if (!$almacen) {
            return response()->json([
                'message' => 'No se encontro el almacén',
            ], 404);
        }
        return response()->json($almacen);
    }

    public function create(CreateAlmRqt $request)
    {
        $data = $request->validated();

        // generar código único
        $data['code'] = $this->generarCodigo();

        // principal default
        $data['is_principal'] = false;

        // verificar principal solo físicos
        $existsPrincipal = Almacen::where('is_principal', true)->exists();

        if (!$existsPrincipal && $data['tipo'] === TipoAlm::FISICO) {
            $data['is_principal'] = true;
        }

        $almacen = Almacen::create($data);

        return response()->json($almacen, 201);
    }


    public function update($id, UpdateAlmRqt $request)
    {
        $almacen = Almacen::findOrFail($id);
        $almacen->update($request->validated());
        return response()->json($almacen);
    }



    public function updateprincipal($id)
    {
        $almacen = Almacen::findOrFail($id);
        if ($almacen->isPrincipal) {
            return response()->json([
                'message' => 'El almacén ya es principal',
            ], 400);
        }
        if ($almacen->tipo != TipoAlm::FISICO) {
            return response()->json([
                'message' => 'El almacén debe ser físico',
            ], 400);
        }
        $almacen->update(['isPrincipal' => true]);
        return response()->json($almacen);
    }
    public function delete($id)
    {
        $almacen = Almacen::findOrFail($id);
        $almacen->delete();
        return response()->json($almacen);
    }


    public function restore($id)
    {
        $almacen = Almacen::withTrashed()->findOrFail($id);
        $almacen->restore();

        return response()->json();
    }
}
