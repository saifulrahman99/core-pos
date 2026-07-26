<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Facades\Activity;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;

class UserService
{
    /**
     * Get paginated users with optional search.
     */
    public function paginate(string $search = '', int $perPage = 15): LengthAwarePaginator
    {
        return User::with(['roles', 'media'])
            ->withCount('media')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get a user by ID with roles and media loaded.
     */
    public function find(int $id): User
    {
        return User::with(['roles', 'media'])->findOrFail($id);
    }

    /**
     * Create a new user with roles and optional avatar.
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (! empty($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            if (! empty($data['avatar'])) {
                $user->addMedia($data['avatar'])->toMediaCollection('avatar');
            }

            Activity::causedBy(auth()->user())->event('user.created')->log("Created user: {$user->name}");

            return $user->load(['roles', 'media']);
        });
    }

    /**
     * Update a user with roles and optional avatar.
     */
    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'is_active' => $data['is_active'] ?? $user->is_active,
            ]);

            if (array_key_exists('roles', $data)) {
                $user->syncRoles($data['roles']);
            }

            if (! empty($data['avatar'])) {
                $user->clearMediaCollection('avatar');
                $user->addMedia($data['avatar'])->toMediaCollection('avatar');
            }

            Activity::causedBy(auth()->user())->event('user.updated')->log("Updated user: {$user->name}");

            return $user->fresh(['roles', 'media']);
        });
    }

    /**
     * Delete a user (soft delete).
     */
    public function delete(User $user): bool
    {
        $name = $user->name;
        $result = $user->delete();

        if ($result) {
            Activity::causedBy(auth()->user())->event('user.deleted')->log("Deleted user: {$name}");
        }

        return $result;
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore(User $user): User
    {
        $user->restore();

        Activity::causedBy(auth()->user())->event('user.restored')->log("Restored user: {$user->name}");

        return $user->fresh(['roles', 'media']) ?? $user;
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $user->roles()->detach();
            $user->clearMediaCollection('avatar');
            $user->forceDelete();

            return true;
        });
    }

    /**
     * Reset a user's password.
     */
    public function resetPassword(User $user, string $password): User
    {
        $user->update([
            'password' => Hash::make($password),
        ]);

        Activity::causedBy(auth()->user())->event('user.password_reset')->log("Reset password for user: {$user->name}");

        return $user;
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user): User
    {
        $newStatus = ! $user->is_active;
        $user->update([
            'is_active' => $newStatus,
        ]);

        $statusText = $newStatus ? 'activated' : 'deactivated';
        Activity::causedBy(auth()->user())->event('user.toggled_active')->log("{$statusText} user: {$user->name}");

        return $user->fresh(['roles', 'media']) ?? $user;
    }

    /**
     * Get all role names.
     */
    public function getAllRoleNames(): Collection
    {
        return Role::pluck('name');
    }
}
