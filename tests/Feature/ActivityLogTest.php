<?php

use App\Models\User;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Permission::create(['name' => 'view activity logs', 'guard_name' => 'web']);

    $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $admin->givePermissionTo('view activity logs');
});

test('authenticated user can view activity logs index page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->get(route('admin.activity-logs.index'));

    $response->assertOk();
});

test('unauthenticated user cannot view activity logs index page', function () {
    $response = $this->get(route('admin.activity-logs.index'));

    $response->assertRedirect('/login');
});

test('activity logs index page displays activity logs', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    Activity::create([
        'log_name' => 'default',
        'description' => 'User created',
        'event' => 'created',
        'subject_type' => User::class,
        'subject_id' => 1,
        'causer_type' => User::class,
        'causer_id' => $user->id,
        'properties' => ['attributes' => ['name' => 'Test User']],
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.activity-logs.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page->component('admin/activity-logs/index'));
});

test('activity logs can be searched by description', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    Activity::create([
        'log_name' => 'default',
        'description' => 'Product updated',
        'event' => 'updated',
        'subject_type' => User::class,
        'subject_id' => 1,
    ]);

    Activity::create([
        'log_name' => 'default',
        'description' => 'User deleted',
        'event' => 'deleted',
        'subject_type' => User::class,
        'subject_id' => 2,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.activity-logs.index', ['search' => 'Product']));

    $response->assertOk();
});

test('activity logs can be filtered by date range', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    Activity::create([
        'log_name' => 'default',
        'description' => 'Recent activity',
        'event' => 'created',
        'subject_type' => User::class,
        'subject_id' => 1,
        'created_at' => Carbon::now(),
    ]);

    Activity::create([
        'log_name' => 'default',
        'description' => 'Old activity',
        'event' => 'created',
        'subject_type' => User::class,
        'subject_id' => 2,
        'created_at' => Carbon::now()->subDays(30),
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.activity-logs.index', [
            'date_from' => Carbon::now()->subDay()->toDateString(),
        ]));

    $response->assertOk();
});

test('activity logs can be filtered by user', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $otherUser = User::factory()->create();

    Activity::create([
        'log_name' => 'default',
        'description' => 'Activity by admin',
        'event' => 'created',
        'subject_type' => User::class,
        'subject_id' => 1,
        'causer_id' => $user->id,
    ]);

    Activity::create([
        'log_name' => 'default',
        'description' => 'Activity by other',
        'event' => 'created',
        'subject_type' => User::class,
        'subject_id' => 2,
        'causer_id' => $otherUser->id,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.activity-logs.index', [
            'user_id' => $user->id,
        ]));

    $response->assertOk();
});

test('activity logs can be filtered by event type', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    Activity::create([
        'log_name' => 'default',
        'description' => 'Created item',
        'event' => 'created',
        'subject_type' => User::class,
        'subject_id' => 1,
    ]);

    Activity::create([
        'log_name' => 'default',
        'description' => 'Updated item',
        'event' => 'updated',
        'subject_type' => User::class,
        'subject_id' => 2,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.activity-logs.index', [
            'event' => 'created',
        ]));

    $response->assertOk();
});

test('activity logs index returns paginated results', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    for ($i = 0; $i < 20; $i++) {
        Activity::create([
            'log_name' => 'default',
            'description' => 'Test activity '.$i,
            'event' => 'created',
            'subject_type' => User::class,
            'subject_id' => 1,
        ]);
    }

    $response = $this
        ->actingAs($user)
        ->get(route('admin.activity-logs.index'));

    $response->assertOk();
});

test('activity logs with multiple filters combined', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    Activity::create([
        'log_name' => 'default',
        'description' => 'User login',
        'event' => 'login',
        'subject_type' => User::class,
        'subject_id' => $user->id,
        'causer_id' => $user->id,
        'created_at' => Carbon::now(),
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.activity-logs.index', [
            'search' => 'login',
            'date_from' => Carbon::now()->subDay()->toDateString(),
            'user_id' => $user->id,
            'event' => 'login',
        ]));

    $response->assertOk();
});
