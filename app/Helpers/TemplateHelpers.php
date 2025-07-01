<?php
// app/Helpers/TemplateHelpers.php

if (!function_exists('get_available_templates')) {
    /**
     * Discover available templates for admin or theme context.
     *
     * @param string $context 'admin', 'theme', or a custom path
     * @param string|null $themeName Optional theme name for theme context
     * @return array [filename => Template Name]
     */
    function get_available_templates($context = 'admin', $themeName = null)
    {
        $templates = [];
        if ($context === 'admin') {
            $templatePath = resource_path('views/admin/templates');
        } else if ($context === 'theme') {
            $theme = $themeName ?: 'modern';
            $templatePath = resource_path("views/themes/{$theme}/templates");
        } else if (is_dir($context)) {
            $templatePath = $context;
        } else {
            $templatePath = resource_path('views/templates');
        }
        if (!is_dir($templatePath)) return $templates;
        foreach (glob($templatePath . '/*.blade.php') as $file) {
            $contents = file_get_contents($file);
            if (preg_match('/Template Name:\s*(.+)/i', $contents, $matches)) {
                $name = trim($matches[1]);
                $filename = basename($file, '.blade.php');
                $templates[$filename] = $name;
            }
        }
        return $templates;
    }
}
