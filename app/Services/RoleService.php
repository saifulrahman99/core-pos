<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleService
{
    /**
     * Get paginated roles with optional search.
     */
    public function paginate(string $search = '', int $perPage = 15): LengthAwarePaginator
    {
        return Role::withCount('users')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get a role by ID with permissions loaded.
     */
    public function find(int $id): Role
    {
        return Role::with('permissions')->findOrFail($id);
    }

    /**
     * Create a new role with permissions.
     */
    public function create(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'web',
            ]);

            if (! empty($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            return $role->load('permissions');
        });
    }

    /**
     * Update a role with permissions.
     */
    public function update(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            $role->update([
                'name' => $data['name'],
            ]);

            $role->syncPermissions($data['permissions'] ?? []);

            return $role->fresh('permissions');
        });
    }

    /**
     * Delete a role.
     */
    public function delete(Role $role): bool
    {
        return DB::transaction(function () use ($role) {
            $role->permissions()->detach();

            return $role->delete();
        });
    }

    /**
     * Get all permissions grouped by domain.
     *
     * @return array<string, Collection<int, Permission>>
     */
    public function getPermissionsGrouped(): array
    {
        $permissions = Permission::orderBy('name')->get();

        return $permissions->groupBy(function (Permission $permission) {
            $parts = explode(' ', $permission->name);

            return ucfirst(end($parts));
        })->sortKeys()->all();
    }

    /**
     * Get all permission names.
     */
    public function getAllPermissionNames(): Collection
    {
        return Permission::pluck('name');
    }
}
