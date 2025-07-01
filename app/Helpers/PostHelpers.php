<?php
// app/Helpers/PostHelpers.php

use App\Models\Post;

if (!function_exists('get_post')) {
    function get_post($id)
    {
        return Post::with(['user', 'category', 'tags', 'meta'])->find($id);
    }
}

if (!function_exists('get_post_by_slug')) {
    function get_post_by_slug($slug, $type = 'post')
    {
        return Post::with(['user', 'category', 'tags', 'meta'])
            ->where('slug', $slug)
            ->where('type', $type)
            ->first();
    }
}

if (!function_exists('get_post_title')) {
    function get_post_title($post)
    {
        return $post ? $post->title : null;
    }
}

if (!function_exists('get_post_excerpt')) {
    function get_post_excerpt($post)
    {
        return $post ? $post->excerpt : null;
    }
}

if (!function_exists('get_post_content')) {
    function get_post_content($post)
    {
        return $post ? $post->content : null;
    }
}

if (!function_exists('get_post_type')) {
    function get_post_type($post)
    {
        return $post ? $post->type : null;
    }
}

if (!function_exists('get_post_meta')) {
    function get_post_meta($post, $key, $default = null)
    {
        return $post ? $post->getMeta($key, $default) : $default;
    }
}

if (!function_exists('set_post_meta')) {
    function set_post_meta($post, $key, $value)
    {
        return $post ? $post->setMeta($key, $value) : null;
    }
}

if (!function_exists('get_posts')) {
    function get_posts($args = [])
    {
        $query = Post::query();
        if (isset($args['type'])) {
            $query->where('type', $args['type']);
        }
        if (isset($args['status'])) {
            $query->where('status', $args['status']);
        }
        if (isset($args['limit'])) {
            $query->limit($args['limit']);
        }
        return $query->orderByDesc('published_at')->get();
    }
}

if (!function_exists('get_post_permalink')) {
    function get_post_permalink($post)
    {
        if (!$post) return null;
        switch ($post->type) {
            case 'page':
                return url('/pages/' . $post->slug);
            case 'news':
                return url('/news/' . $post->slug);
            case 'event':
                return url('/events/' . $post->slug);
            default:
                return url('/blog/' . $post->slug);
        }
    }
}

if (!function_exists('get_post_author')) {
    function get_post_author($post)
    {
        return $post && $post->user ? $post->user->name : null;
    }
}

if (!function_exists('get_post_categories')) {
    function get_post_categories($post)
    {
        return $post && $post->category ? [$post->category] : [];
    }
}

if (!function_exists('get_post_tags')) {
    function get_post_tags($post)
    {
        return $post && $post->tags ? $post->tags : [];
    }
}

if (!function_exists('get_post_featured_image')) {
    /**
     * Get the featured image URL (and optionally the Media model) for a post/page/custom post.
     *
     * @param  \App\Models\Post  $post
     * @param  bool $asMediaModel  Return the Media model instead of just the URL
     * @return string|\App\Models\Media|null
     */
    function get_post_featured_image($post, $asMediaModel = false)
    {
        if (!$post) return null;
        if (method_exists($post, 'featuredImageMedia') && $post->featuredImageMedia) {
            return $asMediaModel ? $post->featuredImageMedia : asset('storage/' . $post->featuredImageMedia->path);
        }
        // Fallback to legacy featured_image path
        if (!empty($post->featured_image)) {
            return $asMediaModel ? null : asset('storage/' . $post->featured_image);
        }
        return null;
    }
}

if (!function_exists('is_post_password_protected')) {
    /**
     * Check if a post is password protected.
     *
     * @param  \App\Models\Post  $post
     * @return bool
     */
    function is_post_password_protected($post)
    {
        return $post && !empty($post->password);
    }
}

if (!function_exists('post_password_required')) {
    /**
     * Check if password is required to view the post content.
     * Logged-in users with appropriate permissions can bypass password protection.
     *
     * @param  \App\Models\Post  $post
     * @return bool
     */
    function post_password_required($post)
    {
        if (!is_post_password_protected($post)) {
            return false;
        }
        
        // Check if user is logged in and has permission to bypass password protection
        $user = auth()->user();
        if ($user) {
            // Administrators and editors can always view protected content
            if ($user->hasRole('administrator') || $user->hasRole('editor')) {
                return false;
            }
            
            // Authors can view their own protected content
            if ($user->hasRole('author') && $post->user_id === $user->id) {
                return false;
            }
        }
        
        // Check if password is already entered and stored in session
        $sessionKey = 'post_password_' . $post->id;
        $enteredPassword = session($sessionKey);
        
        return $enteredPassword !== $post->password;
    }
}

if (!function_exists('get_post_password_form')) {
    /**
     * Generate the password form for protected posts.
     *
     * @param  \App\Models\Post  $post
     * @return string
     */
    function get_post_password_form($post)
    {
        if (!$post) return '';
        
        return view('partials.password-form', ['post' => $post])->render();
    }
}

if (!function_exists('is_post_featured')) {
    /**
     * Check if a post is marked as featured.
     *
     * @param  \App\Models\Post  $post
     * @return bool
     */
    function is_post_featured($post)
    {
        return $post && $post->is_featured;
    }
}

if (!function_exists('get_featured_posts')) {
    /**
     * Get featured posts.
     *
     * @param  array  $args
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function get_featured_posts($args = [])
    {
        $query = Post::query()->where('is_featured', true)->where('status', 'published');
        
        if (isset($args['type'])) {
            $query->where('type', $args['type']);
        }
        
        if (isset($args['limit'])) {
            $query->limit($args['limit']);
        }
        
        return $query->orderByDesc('published_at')->get();
    }
}
