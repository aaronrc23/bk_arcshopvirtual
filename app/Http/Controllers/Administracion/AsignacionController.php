<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\Asignacion\AssignPermissionsToRoleRqt;
use App\Http\Requests\Administracion\Asignacion\AssignPermissionsToUserRqt;
use App\Http\Requests\Administracion\Asignacion\AssignPerModuleRqt;
use App\Http\Requests\Administracion\Asignacion\AssignRoleToUserRqt;
use App\Http\Requests\Administracion\Asignacion\UpdUserRolRqt;
use App\Http\Resources\Administracion\AsignacionResource;
use App\services\Administracion\AsignacionRolesService;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    protected $asignacionRolesService;
    public function __construct(AsignacionRolesService $asignacionRolesService)
    {
        $this->asignacionRolesService = $asignacionRolesService;
    }

    public function listRoles()
    {
        return AsignacionResource::collection($this->asignacionRolesService->listPermissions());
    }

    public function listPermissionsUser($Id)
    {
        return $this->asignacionRolesService->listPermisosUser($Id);
    }

    /**
     * Asignar un rol a un usuario
     */
    public function assignRole(AssignRoleToUserRqt $request)
    {
        $this->asignacionRolesService->assignRoleToUser(
            $request->user_id,
            $request->role
        );

        return response()->json([
            'success' => true,
            'message' => 'Rol asignado correctamente',
        ]);
    }

    /**
     * Actualizar rol de un usuario (reemplaza los anteriores)
     */
    public function updateRole(UpdUserRolRqt $request)
    {
        $this->asignacionRolesService->updateRoleToUser(
            $request->user_id,
            $request->role_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado correctamente',
        ]);
    }

    /**
     * Asignar permiso a un rol
     */
    public function assignPermstoRole(AssignPermissionsToRoleRqt $request)
    {
        $this->asignacionRolesService->assignPermissionToRole(
            $request->role,
            $request->permission
        );

        return response()->json([
            'success' => true,
            'message' => 'Permiso asignado al rol correctamente',
        ]);
    }

    /**
     * Asignar múltiples permisos a un usuario
     */
    public function assignPermissionsToUser(AssignPermissionsToUserRqt $request)
    {
        $this->asignacionRolesService->assignPermissionsToUser(
            $request->user_id,
            $request->permissions
        );

        return response()->json([
            'success' => true,
            'message' => 'Permisos asignados correctamente',
        ]);
    }

    /**
     * Sincronizar permisos de un usuario
     */
    public function syncPermissionsToUser(AssignPermissionsToUserRqt $request)
    {
        try {
            $permissions = $this->asignacionRolesService->syncPermissionsToUser(
                $request->user_id,
                $request->permissions
            );

            return response()->json([
                'success' => true,
                'message' => 'Permisos actualizados correctamente',
                'permissions' => $permissions,
            ]);
        } catch (\DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Asignar permisos por módulo
     */
    public function assignPermissionsByModule(AssignPerModuleRqt $request)
    {
        $this->asignacionRolesService->assignPermissionsByModule(
            $request->user_id,
            $request->modulo
        );

        return response()->json([
            'success' => true,
            'message' => 'Permisos del módulo asignados correctamente',
            'modulo' => $request->modulo,
        ]);
    }
}
