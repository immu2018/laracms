<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostType;

class PostTypeSeeder extends Seeder
{
    public function run()
    {
        PostType::updateOrCreate(['slug' => 'post'], [
            'name' => 'Post',
            'description' => 'Blog posts, articles, news, etc.',
            'is_active' => true,
        ]);
        PostType::updateOrCreate(['slug' => 'page'], [
            'name' => 'Page',
            'description' => 'Static pages (about, contact, etc.)',
            'is_active' => true,
        ]);
    }
}
