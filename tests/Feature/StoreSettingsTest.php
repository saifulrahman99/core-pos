<?php

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('authenticated user can view store settings page', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('store.edit'));

    $response->assertOk();
});

test('unauthenticated user cannot view store settings page', function () {
    Store::factory()->create();

    $response = $this->get(route('store.edit'));

    $response->assertRedirect('/login');
});

test('store settings page displays store data', function () {
    Store::factory()->create([
        'name' => 'My Store',
        'tagline' => 'Best store ever',
        'currency' => 'USD',
    ]);
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('store.edit'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('settings/store')
        ->has('store')
    );
});

test('authenticated user can update store settings', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Updated Store',
            'tagline' => 'New tagline',
            'description' => 'Updated description',
            'currency' => 'USD',
            'timezone' => 'America/New_York',
            'language' => 'en',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('store.edit'));

    $store = Store::first();
    expect($store->name)->toBe('Updated Store');
    expect($store->tagline)->toBe('New tagline');
    expect($store->description)->toBe('Updated description');
    expect($store->currency)->toBe('USD');
    expect($store->timezone)->toBe('America/New_York');
    expect($store->language)->toBe('en');
});

test('store name is required', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => '',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response->assertSessionHasErrors('name');
});

test('store currency is required', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'currency' => '',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response->assertSessionHasErrors('currency');
});

test('store timezone is required', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'currency' => 'IDR',
            'timezone' => '',
            'language' => 'id',
        ]);

    $response->assertSessionHasErrors('timezone');
});

test('store language is required', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => '',
        ]);

    $response->assertSessionHasErrors('language');
});

test('store email must be valid email', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'email' => 'not-an-email',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response->assertSessionHasErrors('email');
});

test('store website must be valid url', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'website' => 'not-a-url',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response->assertSessionHasErrors('website');
});

test('store google maps url must be valid url', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'google_maps_url' => 'not-a-url',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response->assertSessionHasErrors('google_maps_url');
});

test('opening time must match date format', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'opening_time' => 'invalid-time',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response->assertSessionHasErrors('opening_time');
});

test('closing time must match date format', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'closing_time' => 'invalid-time',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response->assertSessionHasErrors('closing_time');
});

test('store can update nullable fields to null', function () {
    Store::factory()->create([
        'phone' => '123456',
        'whatsapp' => '789012',
        'email' => 'test@example.com',
        'address' => 'Some address',
    ]);
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'phone' => '',
            'whatsapp' => '',
            'email' => '',
            'address' => '',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('store.edit'));

    $store = Store::first();
    expect($store->phone)->toBeNull();
    expect($store->whatsapp)->toBeNull();
    expect($store->email)->toBeNull();
    expect($store->address)->toBeNull();
});

test('store can update opening and closing times', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'opening_time' => '09:00',
            'closing_time' => '21:00',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('store.edit'));

    $store = Store::first();
    expect($store->opening_time)->toBe('09:00');
    expect($store->closing_time)->toBe('21:00');
});

test('store can be updated with logo upload', function () {
    Store::factory()->create();
    $user = User::factory()->create();

    Storage::fake('public');

    $logo = UploadedFile::fake()->image('logo.png', 200, 200);

    $response = $this
        ->actingAs($user)
        ->patch(route('store.update'), [
            'name' => 'Test Store',
            'logo' => $logo,
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('store.edit'));

    $store = Store::first();
    expect($store->hasMedia('logo'))->toBeTrue();
});

test('unauthenticated user cannot update store settings', function () {
    Store::factory()->create();

    $response = $this
        ->patch(route('store.update'), [
            'name' => 'Hacked Store',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
        ]);

    $response->assertRedirect('/login');
});
