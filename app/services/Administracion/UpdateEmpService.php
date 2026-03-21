<?php

namespace App\services\Administracion;

use App\Models\Administracion\Empleados;
use App\Models\Auth\Profiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UpdateEmpService
{
    public function updateEmpleado($request, Empleados $empleado)
    {
        return DB::transaction(function () use ($request, $empleado) {

            $empleado->load('user.profile');
            $user = $empleado->user;

            if (!$user) {
                throw ValidationException::withMessages([
                    'user' => ['Usuario asociado no encontrado.']
                ]);
            }

            // 3) Actualizar usuario
            $userData = collect($request->only(['email', 'password']))
                ->filter(fn($v) => filled($v))
                ->when(
                    $request->filled('password'),
                    fn($c) => $c->put('password', bcrypt($c->get('password')))
                )
                ->toArray();

            if ($userData) {
                $user->update($userData);
            }

            // 4) Manejo de foto
            $profile = $user->profile;
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('employees', 'public');

                if ($profile?->photo) {
                    Storage::disk('public')->delete($profile->photo);
                }
            }

            // 5) Actualizar / crear perfil
            $profileData = collect($request->only([
                'name',
                'apellidos',
                'direccion',
                'genero',
                'codigo_postal',
                'departamento',
                'provincia',
                'distrito',
                'referencia',
            ]))->filter(fn($v) => filled($v))->toArray();

            if (isset($path)) {
                $profileData['photo'] = $path;
            }

            if ($profile) {
                if ($profileData) {
                    $profile->update($profileData);
                }
            } else {
                $profileData['user_id'] = $user->id;
                Profiles::create($profileData);
            }

            // 6) Actualizar empleado
            $empleadoData = collect($request->only([
                'phone',
                'dni',
                'direccion',
            ]))->filter(fn($v) => filled($v))->toArray();

            if ($empleadoData) {
                $empleado->update($empleadoData);
            }

            return $empleado->load('user.profile');
        });
    }
}
