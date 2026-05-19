<?php

namespace App\Services\Logistica\Marcas;

use App\Models\Logistica\Marcas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class MarcaService
{

    public function list()
    {
        return Marcas::query()
            ->latest()
            ->get();
    }
    public function create(array $data, ?UploadedFile $file = null)
    {
        return DB::transaction(function () use ($data, $file) {

            $marca = Marcas::create([
                ...$data,

                'slug' => Str::slug($data['nombre']),

                'imagen' => $file
                    ? $file->store('marcas', 'public')
                    : null
            ]);

            return [
                'success' => true,
                'message' => 'Registro exitoso',
                'detail' => 'Marca creada con éxito',
                'data' => $marca
            ];
        });
    }


    public function update(
        int $id,
        array $data,
        ?UploadedFile $file = null
    ) {
        return DB::transaction(function () use ($id, $data, $file) {

            $marca = Marcas::findOrFail($id);

            // Eliminar imagen anterior
            if ($file && $marca->imagen) {

                Storage::disk('public')
                    ->delete($marca->imagen);
            }

            $marca->update([
                ...$data,

                'slug' => Str::slug($data['nombre']),

                'imagen' => $file
                    ? $file->store('marcas', 'public')
                    : $marca->imagen
            ]);

            return [
                'success' => true,
                'message' => 'Actualización exitosa',
                'detail' => 'Marca actualizada con éxito',
                'data' => $marca->fresh()
            ];
        });
    }

    //desactivar marca

    public function desactivar(int $id)
    {
        return DB::transaction(function () use ($id) {

            $marca = Marcas::findOrFail($id);
            $marca->update(['estado' => 'inactivo']);

            return [
                'success' => true,
                'message' => 'Marca desactivada',
                'detail' => 'La marca ha sido desactivada exitosamente',
            ];
        });
    }

    public function reactivar(int $id)
    {
        return DB::transaction(function () use ($id) {

            $marca = Marcas::findOrFail($id);
            $marca->update(['estado' => 'activo']);

            return [
                'success' => true,
                'message' => 'Marca reactivada',
                'detail' => 'La marca ha sido reactivada exitosamente',
            ];
        });
    }


    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {

            $marca = Marcas::findOrFail($id);

            // Eliminar imagen
            if ($marca->imagen) {
                Storage::disk('public')
                    ->delete($marca->imagen);
            }



            $marca->delete();

            return [
                'success' => true,
                'message' => 'Eliminación exitosa',
                'detail' => 'Marca eliminada con éxito',
            ];
        });
    }

    //borrado logico 
    public function restore(int $id)
    {
        return DB::transaction(function () use ($id) {
            $marca = Marcas::withTrashed()->findOrFail($id);
            $marca->restore();
            return [
                'success' => true,
                'message' => 'Restauración exitosa',
                'detail' => 'Marca restaurada con éxito',
            ];
        });
    }

    public function forceDelete(int $id)
    {
        return DB::transaction(function () use ($id) {
            $marca = Marcas::withTrashed()->findOrFail($id);
            // Eliminar imagen
            if ($marca->imagen) {
                Storage::disk('public')
                    ->delete($marca->imagen);
            }

            $marca->forceDelete();
            return [
                'success' => true,
                'message' => 'Eliminación permanente exitosa',
                'detail' => 'Marca eliminada permanentemente con éxito',
            ];
        });
    }
}
