<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResetPasswordRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function index(): Response
    {
        Gate::authorize('viewAny', User::class);

        $users = $this->userService->paginate(
            search: request('search', ''),
            perPage: request('per_page', 15),
        );

        return Inertia::render('admin/users/index', [
            'users' => UserResource::collection($users),
            'filters' => request(['search', 'per_page']),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('admin/users/create', [
            'allRoleNames' => $this->userService->getAllRoleNames(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $this->userService->create($request->validated());

        return to_route('admin.users.index')
            ->with('toast', 'User created successfully.');
    }

    public function show(User $user): Response
    {
        Gate::authorize('view', $user);

        $user = $this->userService->find($user->id);

        return Inertia::render('admin/users/show', [
            'user' => new UserResource($user),
        ]);
    }

    public function edit(User $user): Response
    {
        Gate::authorize('update', $user);

        $user = $this->userService->find($user->id);

        return Inertia::render('admin/users/edit', [
            'user' => new UserResource($user),
            'allRoleNames' => $this->userService->getAllRoleNames(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $this->userService->update($user, $request->validated());

        return to_route('admin.users.index')
            ->with('toast', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $this->userService->delete($user);

        return to_route('admin.users.index')
            ->with('toast', 'User deleted successfully.');
    }

    public function restore(User $user): RedirectResponse
    {
        Gate::authorize('restore', $user);

        $this->userService->restore($user);

        return to_route('admin.users.index')
            ->with('toast', 'User restored successfully.');
    }

    public function resetPassword(ResetPasswordRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $this->userService->resetPassword($user, $request->validated('password'));

        return to_route('admin.users.index')
            ->with('toast', 'Password reset successfully.');
    }

    public function toggleActive(User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $this->userService->toggleActive($user);

        return to_route('admin.users.index')
            ->with('toast', 'User status updated successfully.');
    }
}
