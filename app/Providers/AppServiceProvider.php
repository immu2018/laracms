<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Models\Post;
use App\Observers\PostObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Autoload all helpers in app/Helpers
        foreach (glob(app_path('Helpers') . '/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Log::info('AppServiceProvider booted');
        View::addNamespace('theme', resource_path('views/themes/modern'));
        View::addNamespace('theme-templates', resource_path('views/themes/modern/templates'));
        Blade::component('post-card', \App\View\Components\PostCard::class);
        Post::observe(PostObserver::class);
    }
}
