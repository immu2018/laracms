<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $title = $this->faker->sentence;
        return [
            'title' => $title,
            'content' => $this->faker->paragraphs(3, true),
            'excerpt' => null, // Let model auto-generate
            'category_id' => 1, // Assumes at least one category exists
            'user_id' => 1, // Assumes at least one user exists
            'status' => 'published',
            'published_at' => now(),
            'featured_image' => null,
            'type' => 'post',
        ];
    }

    public function page()
    {
        return $this->state([
            'type' => 'page',
        ]);
    }

    public function custom($type)
    {
        return $this->state([
            'type' => $type,
        ]);
    }
}
