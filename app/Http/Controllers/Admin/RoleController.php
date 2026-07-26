<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService,
    ) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', Role::class);

        $roles = $this->roleService->paginate(
            search: request('search', ''),
            perPage: request('per_page', 15),
        );

        return Inertia::render('admin/roles/index', [
            'roles' => RoleResource::collection($roles),
            'filters' => request(['search', 'per_page']),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Role::class);

        return Inertia::render('admin/roles/create', [
            'permissions' => $this->roleService->getPermissionsGrouped(),
            'allPermissionNames' => $this->roleService->getAllPermissionNames(),
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        Gate::authorize('create', Role::class);

        $this->roleService->create($request->validated());

        return to_route('admin.roles.index')
            ->with('toast', 'Role created successfully.');
    }

    public function edit(Role $role): Response
    {
        Gate::authorize('update', $role);

        $role = $this->roleService->find($role->id);

        return Inertia::render('admin/roles/edit', [
            'role' => new RoleResource($role),
            'permissions' => $this->roleService->getPermissionsGrouped(),
            'allPermissionNames' => $this->roleService->getAllPermissionNames(),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        Gate::authorize('update', $role);

        $this->roleService->update($role, $request->validated());

        return to_route('admin.roles.index')
            ->with('toast', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        Gate::authorize('delete', $role);

        $this->roleService->delete($role);

        return to_route('admin.roles.index')
            ->with('toast', 'Role deleted successfully.');
    }
}
