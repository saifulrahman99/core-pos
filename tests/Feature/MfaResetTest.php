<?php

use App\Mail\MfaResetDisabledMail;
use App\Mail\ResetMfaMail;
use App\Models\MfaResetToken;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    Role::create(['name' => 'admin', 'guard_name' => 'web']);
    Role::create(['name' => 'owner', 'guard_name' => 'web']);
});

test('mfa reset request page renders', function () {
    $response = $this->get(route('mfa.reset.show'));

    $response->assertStatus(200);
});

test('mfa reset request sends email for user with mfa enabled', function () {
    Mail::fake();

    $user = User::factory()->create([
        'two_factor_secret' => 'test-secret',
        'two_factor_recovery_codes' => '["test-code"]',
        'two_factor_confirmed_at' => now(),
    ]);

    $response = $this->post(route('mfa.reset.store'), [
        'email' => $user->email,
    ]);

    $response->assertSessionHas('status');
    Mail::assertQueued(ResetMfaMail::class, function (ResetMfaMail $mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('mfa reset request does not reveal if email does not exist', function () {
    Mail::fake();

    $response = $this->post(route('mfa.reset.store'), [
        'email' => 'nonexistent@example.com',
    ]);

    $response->assertSessionHas('status');
    Mail::assertNothingQueued();
});

test('mfa reset request does not send email for user without mfa', function () {
    Mail::fake();

    $user = User::factory()->create();

    $response = $this->post(route('mfa.reset.store'), [
        'email' => $user->email,
    ]);

    $response->assertSessionHas('status');
    Mail::assertNothingQueued();
});

test('mfa reset verify page renders with valid token', function () {
    $user = User::factory()->create([
        'two_factor_secret' => 'test-secret',
        'two_factor_recovery_codes' => '["test-code"]',
        'two_factor_confirmed_at' => now(),
    ]);

    $token = MfaResetToken::create([
        'user_id' => $user->id,
        'token' => bin2hex(random_bytes(32)),
        'expires_at' => now()->addMinutes(30),
        'used' => false,
    ]);

    $response = $this->get(route('mfa.reset.verify', ['token' => $token->token]));

    $response->assertStatus(200);
});

test('mfa reset verify page shows invalid for expired token', function () {
    $user = User::factory()->create([
        'two_factor_secret' => 'test-secret',
        'two_factor_recovery_codes' => '["test-code"]',
        'two_factor_confirmed_at' => now(),
    ]);

    $token = MfaResetToken::factory()->expired()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->get(route('mfa.reset.verify', ['token' => $token->token]));

    $response->assertStatus(200);
});

test('mfa reset verify page shows invalid for used token', function () {
    $user = User::factory()->create([
        'two_factor_secret' => 'test-secret',
        'two_factor_recovery_codes' => '["test-code"]',
        'two_factor_confirmed_at' => now(),
    ]);

    $token = MfaResetToken::factory()->used()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->get(route('mfa.reset.verify', ['token' => $token->token]));

    $response->assertStatus(200);
});

test('mfa reset verify page shows invalid for nonexistent token', function () {
    $response = $this->get(route('mfa.reset.verify', ['token' => 'nonexistent-token']));

    $response->assertStatus(200);
});

test('mfa is disabled after successful verification', function () {
    Mail::fake();

    $user = User::factory()->create([
        'two_factor_secret' => 'test-secret',
        'two_factor_recovery_codes' => '["test-code"]',
        'two_factor_confirmed_at' => now(),
    ]);

    $token = MfaResetToken::create([
        'user_id' => $user->id,
        'token' => bin2hex(random_bytes(32)),
        'expires_at' => now()->addMinutes(30),
        'used' => false,
    ]);

    $response = $this->post(route('mfa.reset.verify.store', ['token' => $token->token]));

    $response->assertRedirect(route('mfa.reset.success'));

    $user->refresh();
    expect($user->two_factor_secret)->toBeNull();
    expect($user->two_factor_recovery_codes)->toBeNull();
    expect($user->two_factor_confirmed_at)->toBeNull();

    // Token should be marked as used
    $token->refresh();
    expect($token->used)->toBeTrue();

    // Notification email should be sent
    Mail::assertQueued(MfaResetDisabledMail::class, function (MfaResetDisabledMail $mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('mfa reset fails with expired token', function () {
    Mail::fake();

    $user = User::factory()->create([
        'two_factor_secret' => 'test-secret',
        'two_factor_recovery_codes' => '["test-code"]',
        'two_factor_confirmed_at' => now(),
    ]);

    $token = MfaResetToken::factory()->expired()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->post(route('mfa.reset.verify.store', ['token' => $token->token]));

    $response->assertSessionHasErrors('token');

    $user->refresh();
    expect($user->two_factor_secret)->not->toBeNull();
});

test('mfa reset fails with used token', function () {
    Mail::fake();

    $user = User::factory()->create([
        'two_factor_secret' => 'test-secret',
        'two_factor_recovery_codes' => '["test-code"]',
        'two_factor_confirmed_at' => now(),
    ]);

    $token = MfaResetToken::factory()->used()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->post(route('mfa.reset.verify.store', ['token' => $token->token]));

    $response->assertSessionHasErrors('token');

    $user->refresh();
    expect($user->two_factor_secret)->not->toBeNull();
});

test('mfa reset fails with nonexistent token', function () {
    Mail::fake();

    $response = $this->post(route('mfa.reset.verify.store', ['token' => 'nonexistent-token']));

    $response->assertSessionHasErrors('token');

    Mail::assertNothingQueued();
});

test('mfa reset success page renders', function () {
    $response = $this->get(route('mfa.reset.success'));

    $response->assertStatus(200);
});

test('existing tokens are invalidated when requesting new reset', function () {
    Mail::fake();

    $user = User::factory()->create([
        'two_factor_secret' => 'test-secret',
        'two_factor_recovery_codes' => '["test-code"]',
        'two_factor_confirmed_at' => now(),
    ]);

    // Create first token
    $firstToken = MfaResetToken::create([
        'user_id' => $user->id,
        'token' => bin2hex(random_bytes(32)),
        'expires_at' => now()->addMinutes(30),
        'used' => false,
    ]);

    // Request new reset
    $this->post(route('mfa.reset.store'), [
        'email' => $user->email,
    ]);

    // First token should be marked as used
    $firstToken->refresh();
    expect($firstToken->used)->toBeTrue();
});
