<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Permissions grouped by sidebar menu.
     *
     * @var array<string, array<int, string>>
     */
    private const PERMISSIONS = [
        'dashboard' => [
            'view dashboard',
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
        'activity_logs' => [
            'view activity logs',
        ],
        'settings' => [
            'manage store',
            'view profile',
            'edit profile',
            'delete profile',
            'edit store',
            'edit security',
            'edit appearance',
            'update password',
        ],
    ];

    /**
     * Seed the application's permissions.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            }
        }
    }
}
