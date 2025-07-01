<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Setting;
use App\Helpers\TemplateHelper;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'categories', 'tags'])
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->paginate(10);
        // Use theme template for blog index
        return view('theme-templates::landing', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::with(['user', 'categories', 'tags'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();
            
        // Handle password protection - if password required, show password form template
        if (post_password_required($post)) {
            $active = Setting::get('active_theme', 'modern');
            return view("themes.$active.single-post", compact('post'));
        }
            
        $active = Setting::get('active_theme', 'modern');
        $candidates = [];
        if ($post->template) {
            $candidates[] = "themes.$active.templates." . str_replace('.blade.php', '', $post->template);
        }
        $candidates[] = "themes.$active.single-post";
        $candidates[] = "themes.$active.templates.single";
        $candidates[] = "themes.$active.templates.default";
        return TemplateHelper::resolve($candidates, compact('post'));
    }

    public function news()
    {
        $posts = Post::with(['user', 'categories', 'tags'])
            ->where('status', 'published')
            ->where('type', 'news')
            ->orderByDesc('published_at')
            ->paginate(10);
        return view('blog.index', ['posts' => $posts, 'typeLabel' => 'News']);
    }

    public function newsShow($slug)
    {
        $post = Post::with(['user', 'categories', 'tags'])
            ->where('status', 'published')
            ->where('type', 'news')
            ->where('slug', $slug)
            ->firstOrFail();
            
        // Handle password protection
        if (post_password_required($post)) {
            $active = Setting::get('active_theme', 'modern');
            return view("themes.$active.single-post", compact('post'));
        }
            
        $active = Setting::get('active_theme', 'modern');
        $candidates = [];
        if ($post->template) {
            $candidates[] = "themes.$active.templates." . str_replace('.blade.php', '', $post->template);
        }
        $candidates[] = "themes.$active.single-post";
        $candidates[] = "themes.$active.templates.single";
        $candidates[] = "themes.$active.templates.default";
        return TemplateHelper::resolve($candidates, compact('post'));
    }

    public function events()
    {
        $posts = Post::with(['user', 'categories', 'tags'])
            ->where('status', 'published')
            ->where('type', 'event')
            ->orderByDesc('published_at')
            ->paginate(10);
        return view('blog.index', ['posts' => $posts, 'typeLabel' => 'Events']);
    }

    public function eventShow($slug)
    {
        $post = Post::with(['user', 'categories', 'tags'])
            ->where('status', 'published')
            ->where('type', 'event')
            ->where('slug', $slug)
            ->firstOrFail();
            
        // Handle password protection
        if (post_password_required($post)) {
            $active = Setting::get('active_theme', 'modern');
            return view("themes.$active.single-post", compact('post'));
        }
            
        $active = Setting::get('active_theme', 'modern');
        $template = $post->template ? "themes.$active.templates." . str_replace('.blade.php', '', $post->template) : "themes.$active.single-post";
        if ($post->template && view()->exists($template)) {
            return view($template, compact('post'));
        }
        return view("themes.$active.single-post", compact('post'));
    }

    public function page($slug)
    {
        $post = Post::with(['user', 'categories', 'tags'])
            ->where('status', 'published')
            ->where('type', 'page')
            ->where('slug', $slug)
            ->firstOrFail();
            
        // Handle password protection
        if (post_password_required($post)) {
            $active = Setting::get('active_theme', 'modern');
            return view("themes.$active.single-post", compact('post'));
        }
            
        $active = Setting::get('active_theme', 'modern');
        $candidates = [];
        if ($post->template) {
            $candidates[] = "themes.$active.templates." . str_replace('.blade.php', '', $post->template);
        }
        $candidates[] = "themes.$active.templates.page";
        $candidates[] = "themes.$active.single-post";
        $candidates[] = "themes.$active.templates.single-post";
        $candidates[] = "themes.$active.templates.default";
        return \App\Helpers\TemplateHelper::resolve($candidates, compact('post'));
    }
}
