<?php

use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::create(['name' => 'view roles', 'guard_name' => 'web']);
    Permission::create(['name' => 'create roles', 'guard_name' => 'web']);
    Permission::create(['name' => 'edit roles', 'guard_name' => 'web']);
    Permission::create(['name' => 'delete roles', 'guard_name' => 'web']);
    Permission::create(['name' => 'view products', 'guard_name' => 'web']);
    Permission::create(['name' => 'create products', 'guard_name' => 'web']);

    $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $admin->givePermissionTo('view roles');
    $admin->givePermissionTo('create roles');
    $admin->givePermissionTo('edit roles');
    $admin->givePermissionTo('delete roles');
});

test('authenticated user can view roles index page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->get(route('admin.roles.index'));

    $response->assertOk();
});

test('unauthenticated user cannot view roles index page', function () {
    $response = $this->get(route('admin.roles.index'));

    $response->assertRedirect('/login');
});

test('roles index page displays roles', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    Role::create(['name' => 'editor', 'guard_name' => 'web']);
    Role::create(['name' => 'viewer', 'guard_name' => 'web']);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.roles.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/roles/index')
        ->has('roles')
    );
});

test('authenticated user can view create role page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->get(route('admin.roles.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/roles/create')
        ->has('permissions')
        ->has('allPermissionNames')
    );
});

test('authenticated user can create a role', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.roles.store'), [
            'name' => 'editor',
            'permissions' => ['view products', 'create products'],
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.roles.index'));

    $role = Role::where('name', 'editor')->first();
    expect($role)->not->toBeNull();
    expect($role->hasPermissionTo('view products'))->toBeTrue();
    expect($role->hasPermissionTo('create products'))->toBeTrue();
});

test('role name is required', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.roles.store'), [
            'name' => '',
            'permissions' => ['view products'],
        ]);

    $response->assertSessionHasErrors('name');
});

test('role name must be unique', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.roles.store'), [
            'name' => 'admin',
            'permissions' => ['view products'],
        ]);

    $response->assertSessionHasErrors('name');
});

test('permissions are required', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.roles.store'), [
            'name' => 'new-role',
            'permissions' => [],
        ]);

    $response->assertSessionHasErrors('permissions');
});

test('permission must exist in database', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.roles.store'), [
            'name' => 'new-role',
            'permissions' => ['nonexistent permission'],
        ]);

    $response->assertSessionHasErrors('permissions.0');
});

test('authenticated user can view edit role page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
    $role->givePermissionTo('view products');

    $response = $this
        ->actingAs($user)
        ->get(route('admin.roles.edit', $role));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/roles/edit')
        ->has('role')
        ->has('permissions')
    );
});

test('authenticated user can update a role', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
    $role->givePermissionTo('view products');

    $response = $this
        ->actingAs($user)
        ->patch(route('admin.roles.update', $role), [
            'name' => 'senior-editor',
            'permissions' => ['view products', 'create products'],
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.roles.index'));

    $role->refresh();
    expect($role->name)->toBe('senior-editor');
    expect($role->hasPermissionTo('view products'))->toBeTrue();
    expect($role->hasPermissionTo('create products'))->toBeTrue();
});

test('role name must be unique on update', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);

    $response = $this
        ->actingAs($user)
        ->patch(route('admin.roles.update', $role), [
            'name' => 'admin',
            'permissions' => ['view products'],
        ]);

    $response->assertSessionHasErrors('name');
});

test('authenticated user can delete a role', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);

    $response = $this
        ->actingAs($user)
        ->delete(route('admin.roles.destroy', $role));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.roles.index'));

    expect(Role::where('name', 'editor')->exists())->toBeFalse();
});

test('roles index supports search', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    Role::create(['name' => 'editor', 'guard_name' => 'web']);
    Role::create(['name' => 'viewer', 'guard_name' => 'web']);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.roles.index', ['search' => 'edit']));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->has('roles.data', 1)
    );
});
