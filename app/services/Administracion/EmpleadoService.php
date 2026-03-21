<?php

namespace App\Services\Administracion;

use App\Http\Requests\Administracion\EmpleadoCreateRqt;
use App\Models\Administracion\Empleados;
use App\Models\Administracion\Empresa;
use App\Models\Auth\Profiles;
use App\Models\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmpleadoService
{
    public function list()
    {
        $empleado = Empleados::with('user.profile')->get();
        return $empleado;
    }
    public function registerEmpleado(EmpleadoCreateRqt $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            $empresa = Empresa::first();
            if (!$empresa) {
                return response()->json([
                    'message' => 'No hay empresa registrada'
                ], 404);
            }

            // 🔐 Verificación extra (defensa en profundidad)
            if (User::where('email', $request->email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => ['Este correo ya está registrado'],
                ]);
            }

            if ($request->dni && Empleados::where('dni', $request->dni)->exists()) {
                throw ValidationException::withMessages([
                    'dni' => ['Ya existe un empleado con este DNI'],
                ]);
            }

            // 1️⃣ Crear usuario
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // 2️⃣ Crear perfil
            Profiles::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'apellidos' => $request->apellidos,
                'direccion' => $request->direccion,
                'genero' => $request->genero,
                'codigo_postal' => $request->codigo_postal,
                'departamento' => $request->departamento,
                'provincia' => $request->provincia,
                'distrito' => $request->distrito,
                'referencia' => $request->referencia,
            ]);

            // 3️⃣ Crear empleado
            Empleados::create([
                'user_id' => $user->id,
                'phone' => $data['phone'],
                'dni' => $data['dni'],
                'direccion' => $data['direccion'],
                'empresa_id' => $empresa->id,
            ]);

            return [
                'success' => true,
                'message' => 'Empleado registrado correctamente',
            ];
        });
    }


    public function deleteEmpleado(int $empleadoId): void
    {
        DB::transaction(function () use ($empleadoId) {

            $empleado = Empleados::with('user.profile')
                ->lockForUpdate()
                ->find($empleadoId);



            if (!$empleado) {
                throw ValidationException::withMessages([
                    'empleado' => ['Empleado no encontrado.']
                ]);
            }

            // 1️⃣ Soft delete profile
            if ($empleado->user?->profile) {
                $empleado->user->profile->delete();
            }

            // 2️⃣ Soft delete user
            if ($empleado->user) {
                $empleado->user->delete();
            }

            // 3️⃣ Soft delete empleado
            $empleado->delete();
        });
    }


    public function restoreEmpleado(int $empleadoId): void
    {
        DB::transaction(function () use ($empleadoId) {

            $empleado = Empleados::withTrashed()
                ->with([
                    'user' => fn($q) => $q->withTrashed()
                        ->with(['profile' => fn($p) => $p->withTrashed()])
                ])
                ->lockForUpdate()
                ->find($empleadoId);

            if (!$empleado) {
                throw ValidationException::withMessages([
                    'empleado' => ['Empleado no encontrado.']
                ]);
            }

            // 1️⃣ Restaurar user
            if ($empleado->user && $empleado->user->trashed()) {
                $empleado->user->restore();
            }

            // 2️⃣ Restaurar profile
            if ($empleado->user?->profile && $empleado->user->profile->trashed()) {
                $empleado->user->profile->restore();
            }

            // 3️⃣ Restaurar empleado
            if ($empleado->trashed()) {
                $empleado->restore();
            }
        });
    }
}
