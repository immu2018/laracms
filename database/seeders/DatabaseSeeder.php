<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RolesTableSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(UserAndRoleSeeder::class);
        $this->call(PostTypeSeeder::class);

        // Seed posts with different types
        Post::factory()->count(5)->create(); // Regular posts
        Post::factory()->count(2)->page()->create(); // Pages
        Post::factory()->count(2)->custom('news')->create(); // Custom type: news
        Post::factory()->count(2)->custom('event')->create(); // Custom type: event
    }
}
