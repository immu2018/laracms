<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Setting;

class ThemeController extends Controller
{
    public function index()
    {
        $themeDirs = File::directories(resource_path('views/themes'));
        $themes = collect($themeDirs)->map(function ($dir) {
            $key = basename($dir);
            $meta = [
                'key' => $key,
                'name' => ucfirst($key),
                'description' => null,
                'screenshot' => null,
            ];
            $json = $dir . '/theme.json';
            if (File::exists($json)) {
                $data = json_decode(File::get($json), true);
                $meta = array_merge($meta, $data);
                if (isset($meta['screenshot'])) {
                    $meta['screenshot'] = 'themes/' . $key . '/' . ltrim($meta['screenshot'], '/');
                }
            }
            return $meta;
        });
        $activeTheme = Setting::get('active_theme', 'default');
        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    public function activate(Request $request)
    {
        $theme = $request->input('theme');
        Setting::set('active_theme', $theme);
        return redirect()->route('admin.themes.index')->with('success', 'Theme activated!');
    }
}
