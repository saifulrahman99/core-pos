<?php

namespace Database\Factories;

use App\Models\MfaResetToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MfaResetToken>
 */
class MfaResetTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token' => bin2hex(random_bytes(32)),
            'expires_at' => now()->addMinutes(30),
            'used' => false,
        ];
    }

    /**
     * Mark the token as used.
     */
    public function used(): static
    {
        return $this->state(fn () => ['used' => true]);
    }

    /**
     * Mark the token as expired.
     */
    public function expired(): static
    {
        return $this->state(fn () => ['expires_at' => now()->subMinute()]);
    }
}
