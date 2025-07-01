<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\SitePasswordController;
use App\Http\Controllers\Admin\SettingsController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $active = Setting::get('active_theme', 'modern');
    $view = "themes.$active.frontpage";
    if (view()->exists($view)) {
        return view($view);
    }
    abort(404, 'Frontpage not found for active theme.');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Password protection route
Route::post('/post/password', [PasswordController::class, 'submit'])->name('post.password');

// Site password protection routes (must be before middleware)
Route::get('/site-password', [SitePasswordController::class, 'showForm'])->name('site-password.form');
Route::post('/site-password-check', [SitePasswordController::class, 'checkPassword'])->name('site-password.check');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/bulk-role', [UserManagementController::class, 'bulkRole'])->name('users.bulk-role');
    Route::post('/users/bulk-delete', [UserManagementController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::resource('tags', TagController::class);
    Route::resource('menus', MenuController::class);
    Route::post('menus/{menu}/items', [MenuItemController::class, 'store'])->name('menu-items.store');
    Route::put('menus/{menu}/items/{item}', [MenuItemController::class, 'update'])->name('menu-items.update');
    Route::delete('menus/{menu}/items/{item}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');
    Route::post('menus/{menu}/order', [MenuController::class, 'order'])->name('menus.order');
    Route::post('menus/{menu}/order-nested', [MenuController::class, 'orderNested'])->name('menus.orderNested');
    Route::get('themes', [\App\Http\Controllers\Admin\ThemeController::class, 'index'])->name('themes.index');
    Route::post('themes/activate', [\App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('themes.activate');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware(['auth', 'role:administrator|editor|author|contributor'])->prefix('admin')->name('admin.')->group(function () {
    // Categories
    Route::resource('categories', CategoryController::class);
    // Media
    Route::get('media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
    Route::post('media', [\App\Http\Controllers\Admin\MediaController::class, 'store'])->name('media.store');
    Route::delete('media/{media}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');
    Route::post('media/bulk', [\App\Http\Controllers\Admin\MediaController::class, 'bulkDestroy'])->name('media.bulk-destroy');

    // Custom post actions (must be above resource route)
    Route::post('posts/bulk-destroy', [PostController::class, 'bulkDestroy'])->name('posts.bulk-destroy');
    Route::post('posts/bulk-status', [PostController::class, 'bulkStatus'])->name('posts.bulk-status');
    Route::get('posts/trash', [PostController::class, 'trash'])->name('posts.trash');
    Route::post('posts/restore', [PostController::class, 'restore'])->name('posts.restore');
    Route::post('posts/force-destroy', [PostController::class, 'forceDestroy'])->name('posts.force-destroy');
    Route::get('posts/{post}/duplicate', [PostController::class, 'duplicate'])->name('posts.duplicate');
    // Resource route must come after all custom actions
    Route::resource('posts', PostController::class);

    // Pages management (same as posts, but type = 'page')
    Route::get('/pages', [PostController::class, 'index'])->name('pages.index')->defaults('type', 'page');
    Route::get('/pages/create', [PostController::class, 'create'])->name('pages.create')->defaults('type', 'page');
    Route::post('/pages', [PostController::class, 'store'])->name('pages.store')->defaults('type', 'page');
    Route::get('/pages/{post}/edit', [PostController::class, 'edit'])->name('pages.edit')->defaults('type', 'page');
    Route::put('/pages/{post}', [PostController::class, 'update'])->name('pages.update')->defaults('type', 'page');
    Route::delete('/pages/{post}', [PostController::class, 'destroy'])->name('pages.destroy')->defaults('type', 'page');
});

Route::get('/blog', [\App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::get('/news', [\App\Http\Controllers\BlogController::class, 'news'])->name('news.index');
Route::get('/news/{slug}', [\App\Http\Controllers\BlogController::class, 'newsShow'])->name('news.show');
Route::get('/events', [\App\Http\Controllers\BlogController::class, 'events'])->name('events.index');
Route::get('/events/{slug}', [\App\Http\Controllers\BlogController::class, 'eventShow'])->name('events.show');
Route::get('/pages/{slug}', [\App\Http\Controllers\BlogController::class, 'page'])->name('pages.show');

// Debug route to check site settings
Route::get('/debug-settings', function() {
    return [
        'site_password_enabled' => \App\Models\SiteSetting::get('site_password_enabled'),
        'site_password' => \App\Models\SiteSetting::get('site_password'),
        'session_password' => session('site_password_verified'),
        'user_authenticated' => auth()->check(),
    ];
});

// Test route to verify session after password entry
Route::get('/test-session', function() {
    return [
        'session_password' => session('site_password_verified'),
        'all_session' => session()->all(),
        'user_authenticated' => auth()->check(),
    ];
});

// Simple welcome route for testing
Route::get('/welcome', function() {
    return view('welcome');
});

// Site logout route
Route::post('/site-logout', [SitePasswordController::class, 'logout'])->name('site-password.logout');

require __DIR__.'/auth.php';
