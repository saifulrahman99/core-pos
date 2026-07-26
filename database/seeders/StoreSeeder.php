<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Seed the application's store.
     */
    public function run(): void
    {
        Store::factory()->create();
    }
}
