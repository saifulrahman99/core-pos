<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Default permissions grouped by domain.
     *
     * @var array<string, array<int, string>>
     */
    private const PERMISSIONS = [
        'settings' => [
            'manage store',
        ],
        'roles' => [
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
        ],
        'users' => [
            'view users',
            'create users',
            'edit users',
            'delete users',
        ],
        'products' => [
            'view products',
            'create products',
            'edit products',
            'delete products',
        ],
        'orders' => [
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
        ],
        'reports' => [
            'view reports',
        ],
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        $adminPermissions = Permission::pluck('name')->toArray();

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($adminPermissions);

        $cashier = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $cashier->syncPermissions([
            'view products',
            'view orders',
            'create orders',
        ]);

        $kitchen = Role::firstOrCreate(['name' => 'kitchen', 'guard_name' => 'web']);
        $kitchen->syncPermissions([
            'view orders',
            'edit orders',
        ]);

        $firstUser = User::first();
        if ($firstUser && ! $firstUser->hasRole('admin')) {
            $firstUser->assignRole('admin');
        }
    }
}
