<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'tagline' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'phone' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'website' => fake()->url(),
            'address' => fake()->address(),
            'google_maps_url' => fake()->url(),
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'language' => 'id',
            'receipt_header' => fake()->sentence(4),
            'receipt_footer' => fake()->sentence(4),
            'opening_time' => '08:00',
            'closing_time' => '22:00',
        ];
    }
}
