<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's categories.
     */
    public function run(): void
    {
        if (Category::count() > 0) {
            return;
        }

        $categories = [
            ['name' => 'Beverages', 'slug' => 'beverages', 'description' => 'Drinks and beverages', 'status' => true, 'sort_order' => 0],
            ['name' => 'Food', 'slug' => 'food', 'description' => 'Food items', 'status' => true, 'sort_order' => 1],
            ['name' => 'Snacks', 'slug' => 'snacks', 'description' => 'Snack items', 'status' => true, 'sort_order' => 2],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}