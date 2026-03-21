<?php

namespace App\services\Administracion;

use App\Models\Administracion\Empleados;
use App\Models\Auth\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AsignacionRolesService
{

    public function listPermisosUser($Id)
    {
        $empleado = Empleados::find($Id);
        if (!$empleado) {
            throw new \DomainException("No existe el empleado con el id {$Id}");
        }
        $user = User::find($empleado->user_id);
        if (!$user) {
            throw new \DomainException("No existe el usuario con el id {$empleado->user_id}");
        }
        return $user->getPermissionNames()->toArray();
    }
    public function listPermissions(): array
    {
        return Permission::all()->toArray();
    }
    public function assignPermissionToRole(string $roleName, string $permissionName): void
    {
        $role = Role::findByName($roleName);
        $role->givePermissionTo($permissionName);
    }

    public function assignRoleToUser(int $userId, string $roleName): void
    {
        $user = User::findOrFail($userId);
        $user->assignRole($roleName);
    }

    public function updateRoleToUser(int $userId, int $roleId): void
    {
        $user = User::findOrFail($userId);
        $role = Role::findOrFail($roleId);

        $user->syncRoles([$role->name]);
    }

    public function assignPermissionsToUser(int $userId, array $permissions): void
    {
        $user = User::findOrFail($userId);
        $user->givePermissionTo($permissions);
    }

    public function syncPermissionsToUser(int $userId, array $permissions): array
    {
        $empleado = Empleados::find($userId);
        if (!$empleado) {
            throw new \DomainException("No existe el empleado con el id {$userId}");
        }
        $user = User::find($empleado->user_id);
        if (!$user) {
            throw new \DomainException("No existe el usuario con el id {$empleado->user_id}");
        }

        $mapped = collect($permissions)
            ->map(
                fn($p) =>
                str_contains($p, '.')
                    ? implode(' ', array_reverse(explode('.', $p, 2)))
                    : $p
            )
            ->unique()
            ->values()
            ->all();

        $existing = Permission::whereIn('name', $mapped)->pluck('name')->all();

        $missing = array_diff($mapped, $existing);
        if ($missing) {
            throw new \DomainException(
                'Permisos no existentes: ' . implode(', ', $missing)
            );
        }

        $user->syncPermissions($mapped);

        return $user->getPermissionNames()->toArray();
    }

    public function assignPermissionsByModule(int $userId, string $module): void
    {
        $user = User::findOrFail($userId);

        $permissions = Permission::where('name', 'like', "% {$module}")
            ->pluck('name')
            ->toArray();

        if (empty($permissions)) {
            throw new \DomainException("No existen permisos para el módulo {$module}");
        }

        $user->givePermissionTo($permissions);
    }
}
