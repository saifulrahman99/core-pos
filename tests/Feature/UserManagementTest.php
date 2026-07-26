<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::create(['name' => 'view users', 'guard_name' => 'web']);
    Permission::create(['name' => 'create users', 'guard_name' => 'web']);
    Permission::create(['name' => 'edit users', 'guard_name' => 'web']);
    Permission::create(['name' => 'delete users', 'guard_name' => 'web']);

    $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $admin->givePermissionTo('view users');
    $admin->givePermissionTo('create users');
    $admin->givePermissionTo('edit users');
    $admin->givePermissionTo('delete users');
});

test('authenticated user can view users index page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->get(route('admin.users.index'));

    $response->assertOk();
});

test('unauthenticated user cannot view users index page', function () {
    $response = $this->get(route('admin.users.index'));

    $response->assertRedirect('/login');
});

test('users index page displays users', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.users.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/users/index')
        ->has('users')
    );
});

test('authenticated user can view create user page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->get(route('admin.users.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/users/create')
        ->has('allRoleNames')
    );
});

test('authenticated user can create a user', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => true,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'is_active' => true,
    ]);
});

test('user email is required', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertSessionHasErrors('email');
});

test('user email must be unique', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertSessionHasErrors('email');
});

test('password is required for new user', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);

    $response->assertSessionHasErrors('password');
});

test('authenticated user can view edit user page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $targetUser = User::factory()->withTwoFactor()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('admin.users.edit', $targetUser));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/users/edit')
        ->has('user')
        ->has('allRoleNames')
    );
});

test('authenticated user can update a user', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $targetUser = User::factory()->withTwoFactor()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('admin.users.update', $targetUser), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('users', [
        'id' => $targetUser->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

test('user email must be unique on update', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $targetUser = User::factory()->withTwoFactor()->create();
    $otherUser = User::factory()->withTwoFactor()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('admin.users.update', $targetUser), [
            'name' => 'Updated Name',
            'email' => $otherUser->email,
        ]);

    $response->assertSessionHasErrors('email');
});

test('authenticated user can delete a user', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $targetUser = User::factory()->withTwoFactor()->create();

    $response = $this
        ->actingAs($user)
        ->delete(route('admin.users.destroy', $targetUser));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.users.index'));

    $this->assertSoftDeleted('users', ['id' => $targetUser->id]);
});

test('authenticated user can view user details page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $targetUser = User::factory()->withTwoFactor()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('admin.users.show', $targetUser));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/users/show')
        ->has('user')
    );
});

test('authenticated user can reset user password', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $targetUser = User::factory()->withTwoFactor()->create();

    $response = $this
        ->actingAs($user)
        ->post(route('admin.users.resetPassword', $targetUser), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.users.index'));
});

test('authenticated user can toggle user active status', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $targetUser = User::factory()->create(['is_active' => true]);

    $response = $this
        ->actingAs($user)
        ->post(route('admin.users.toggleActive', $targetUser));

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.users.index'));

    $targetUser->refresh();
    expect($targetUser->is_active)->toBeFalse();
});

test('users index supports search', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    User::factory()->create(['name' => 'John Doe']);
    User::factory()->create(['name' => 'Jane Smith']);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.users.index', ['search' => 'John Doe']));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->has('users.data', 1)
    );
});

test('users can be assigned roles on create', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    Role::create(['name' => 'cashier', 'guard_name' => 'web']);

    $response = $this
        ->actingAs($user)
        ->post(route('admin.users.store'), [
            'name' => 'Cashier User',
            'email' => 'cashier@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles' => ['cashier'],
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.users.index'));

    $createdUser = User::where('email', 'cashier@example.com')->first();
    expect($createdUser->hasRole('cashier'))->toBeTrue();
});
