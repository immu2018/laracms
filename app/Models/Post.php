<?php

namespace App\Models;

use App\Traits\HasMeta;
use App\Traits\HasSlug;
use App\Traits\HasExcerpt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, HasMeta, HasSlug, HasExcerpt, SoftDeletes;

    protected $fillable = ['title', 'slug', 'content', 'excerpt', 'user_id', 'status', 'published_at', 'featured_image', 'featured_image_media_id', 'type', 'template', 'password', 'is_featured'];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
            if (empty($post->excerpt)) {
                $post->excerpt = $post->generateExcerpt($post->content);
            }
        });
        static::updating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
            if (empty($post->excerpt)) {
                $post->excerpt = $post->generateExcerpt($post->content);
            }
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function meta()
    {
        return $this->hasMany(PostMeta::class);
    }

    public function postType()
    {
        return $this->belongsTo(PostType::class, 'type', 'slug');
    }

    public function featuredImageMedia()
    {
        return $this->belongsTo(\App\Models\Media::class, 'featured_image_media_id');
    }
}
