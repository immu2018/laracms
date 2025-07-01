<?php
use App\Models\Setting;
use Illuminate\Support\Facades\File;

if (!function_exists('get_theme_templates')) {
    function get_theme_templates() {
        $active = Setting::get('active_theme', 'modern');
        $templatePath = resource_path("views/themes/{$active}/templates");
        $templates = [];
        if (File::exists($templatePath)) {
            foreach (File::files($templatePath) as $file) {
                $filename = $file->getFilename();
                if (str_ends_with($filename, '.blade.php')) {
                    $contents = File::get($file->getPathname());
                    // Match Template Name: ... inside any Blade comment (multi-line or single-line)
                    if (preg_match('/\{\{--([\s\S]*?)--\}\}/', $contents, $commentBlock)) {
                        if (preg_match('/Template Name:\s*(.+)/i', $commentBlock[1], $matches)) {
                            $name = trim($matches[1]);
                        } else {
                            $name = ucwords(str_replace(['-', '_', '.blade.php'], [' ', ' ', ''], $filename));
                        }
                    } else {
                        $name = ucwords(str_replace(['-', '_', '.blade.php'], [' ', ' ', ''], $filename));
                    }
                    $templates[$filename] = $name;
                }
            }
        }
        return $templates;
    }
}
