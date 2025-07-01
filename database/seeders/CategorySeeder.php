<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate([
            'slug' => 'uncategorized',
        ], [
            'name' => 'Uncategorized',
            'description' => 'Default category for uncategorized posts.'
        ]);
    }
}
