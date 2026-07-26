<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'owner', 'guard_name' => 'web']);
    Role::create(['name' => 'cashier', 'guard_name' => 'web']);
});

test('security page shows isTwoFactorRequired for admin role', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->where('isTwoFactorRequired', true)
            ->where('twoFactorEnabled', false),
        );
});

test('security page shows isTwoFactorRequired for owner role', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->where('isTwoFactorRequired', true)
            ->where('twoFactorEnabled', false),
        );
});

test('security page shows isTwoFactorRequired false for non-admin roles', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();
    $user->assignRole('cashier');

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->where('isTwoFactorRequired', false),
        );
});

test('security page shows twoFactorEnabled true when user has 2fa configured', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->where('twoFactorEnabled', true)
            ->where('isTwoFactorRequired', false),
        );
});

test('admin user without 2fa is redirected by force middleware', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertRedirect(route('security.edit'));
});

test('owner user without 2fa is redirected by force middleware', function () {
    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertRedirect(route('security.edit'));
});

test('admin user with 2fa can access admin routes', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertOk();
});

test('non-admin user without 2fa can access admin routes', function () {
    $user = User::factory()->create();
    $user->assignRole('cashier');

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertOk();
});

test('admin user with 2fa cannot disable it', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->deleteJson('/user/two-factor-authentication')
        ->assertForbidden();
});

test('owner user with 2fa cannot disable it', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('owner');

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->deleteJson('/user/two-factor-authentication')
        ->assertForbidden();
});

test('non-admin user can disable 2fa', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('cashier');

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->deleteJson('/user/two-factor-authentication')
        ->assertOk();

    expect($user->refresh()->hasEnabledTwoFactorAuthentication())->toBeFalse();
});

test('security page without 2fa feature does not show isTwoFactorRequired', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    config(['fortify.features' => []]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->missing('isTwoFactorRequired')
            ->missing('twoFactorEnabled'),
        );
});

test('unauthenticated user is redirected to login when accessing admin routes', function () {
    $this->get(route('admin.users.index'))
        ->assertRedirect('/login');
});

test('security page renders warning banner props for admin without 2fa', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->where('isTwoFactorRequired', true)
            ->where('twoFactorEnabled', false)
            ->where('canManageTwoFactor', true),
        );
});

test('force middleware redirects to security edit with flash message', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertRedirect(route('security.edit'))
        ->assertSessionHas('flash');
});
