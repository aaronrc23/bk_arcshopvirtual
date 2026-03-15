<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['superadmin', 'admin', 'empleado', 'cliente', 'dev'];
        foreach ($roles as $r) {
            Role::firstOrCreate([
                'name' => $r,
                'guard_name' => 'web'
            ]);
        }

        // 2️⃣ Permisos

        $modules = [
            'usuarios',
            'roles',
            'productos',
            'categorias',
            'clientes',
            'inventarios',
            'unidades',
            'metodopago',
        ];
        $defaultActions = ['ver', 'crear', 'editar', 'eliminar'];

        $customModules = [
            'reportes' => ['ver'],
            'comprobantes' => ['ver', 'crear', 'editar', 'anular'],
            'configuraciones' => ['ver', 'editar']
        ];

        foreach ($modules as $module) {
            foreach ($defaultActions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action} {$module}",
                    'guard_name' => 'web'
                ]);
            }
        }

        foreach ($customModules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action} {$module}",
                    'guard_name' => 'web'
                ]);
            }
        }

        // 3️⃣ Asignar permisos
        $admin = Role::where('name', 'admin')->first();
        $admin->syncPermissions([
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'ver productos',
            'crear productos',
            'editar productos'
        ]);

        $empleado = Role::where('name', 'empleado')->first();
        $empleado->syncPermissions([
            'ver productos',
            'ver clientes',
            'crear comprobantes'
        ]);

        // 4️⃣ Superadmin = todos los permisos
        Role::whereIn('name', ['superadmin', 'dev'])
            ->each(fn($role) => $role->syncPermissions(Permission::all()));

        // 5️⃣ Crear usuario SuperAdmin
        $user = User::firstOrCreate(
            ['email' => 'super@demo.com'],
            ['password' => Hash::make('admin123')]
        );

        $user->syncRoles(['superadmin']);

        $user->profile()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Super',
                'apellidos' => 'Admin',
            ]
        );
        // 7️⃣ Asignar rol
        if (!$user->hasRole('superadmin')) {
            $user->assignRole('superadmin');
        }
        $this->command->info('✅ Roles, permisos, usuario y profile creados correctamente.');
    }
}
