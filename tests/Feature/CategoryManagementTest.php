<?php

use App\Models\Category;
use App\Models\User;

beforeEach(function () {
    $admin = \Spatie\Permission\Models\Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $admin->givePermissionTo([
        'view categories',
        'create categories',
        'edit categories',
        'delete categories',
    ]);
});

test('authenticated user can view categories index page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->get(route('admin.categories.index'));

    $response->assertOk();
});

test('unauthenticated user cannot view categories index page', function () {
    $response = $this->get(route('admin.categories.index'));

    $response->assertRedirect('/login');
});

test('categories index page displays categories', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    Category::create(['name' => 'Beverages', 'slug' => 'beverages']);
    Category::create(['name' => 'Food', 'slug' => 'food']);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.categories.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/categories/index')
        ->has('categories')
    );
});

test('authenticated user can view create category page', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->get(route('admin.categories.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/categories/create')
    );
});

test('authenticated user can create a category', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this
        ->actingAs($user)
        ->post(route('admin.categories.store'), [
            'name' => 'Beverages',
            'slug' => 'beverages',
            'description' => 'Drinks and beverages',
            'status' => true,
            'sort_order' => 0,
        ]);

    $response->assertRedirect(route('admin.categories.index'));
    expect(Category::where('slug', 'beverages')->exists())->toBeTrue();
});

test('authenticated user can edit a category', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $category = Category::create(['name' => 'Beverages', 'slug' => 'beverages']);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.categories.edit', $category));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/categories/edit')
        ->has('category')
    );
});

test('authenticated user can update a category', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $category = Category::create(['name' => 'Beverages', 'slug' => 'beverages']);

    $response = $this
        ->actingAs($user)
        ->patch(route('admin.categories.update', $category), [
            'name' => 'Drinks',
            'slug' => 'drinks',
            'description' => 'Updated description',
            'status' => false,
            'sort_order' => 5,
        ]);

    $response->assertRedirect(route('admin.categories.index'));
    expect($category->fresh()->name)->toBe('Drinks');
    expect($category->fresh()->slug)->toBe('drinks');
    expect($category->fresh()->status)->toBeFalse();
});

test('authenticated user can delete a category', function () {
    $user = User::factory()->withTwoFactor()->create();
    $user->assignRole('admin');

    $category = Category::create(['name' => 'Beverages', 'slug' => 'beverages']);

    $response = $this
        ->actingAs($user)
        ->delete(route('admin.categories.destroy', $category));

    $response->assertRedirect(route('admin.categories.index'));
    expect(Category::where('slug', 'beverages')->withTrashed()->exists())->toBeTrue();
});