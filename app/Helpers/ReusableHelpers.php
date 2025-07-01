<?php
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\Menu;

if (!function_exists('get_category')) {
    function get_category($id) {
        return Category::find($id);
    }
}

if (!function_exists('get_categories')) {
    function get_categories($args = []) {
        $query = Category::query();
        if (isset($args['parent_id'])) {
            $query->where('parent_id', $args['parent_id']);
        }
        return $query->get();
    }
}

if (!function_exists('get_tag')) {
    function get_tag($id) {
        return Tag::find($id);
    }
}

if (!function_exists('get_tags')) {
    function get_tags($args = []) {
        $query = Tag::query();
        return $query->get();
    }
}

if (!function_exists('get_user')) {
    function get_user($id) {
        return User::find($id);
    }
}

if (!function_exists('get_menu')) {
    function get_menu($location) {
        return Menu::where('location', $location)->first();
    }
}

if (!function_exists('get_theme_option')) {
    function get_theme_option($key, $default = null) {
        // Placeholder for future theme options table
        return $default;
    }
}

if (!function_exists('render_menu')) {
    function render_menu($location, $view = null) {
        $items = get_menu_items($location);
        if ($view) {
            return view($view, ['items' => $items])->render();
        }
        // Default: render as ul/li
        $html = '<ul>';
        foreach ($items as $item) {
            $html .= '<li><a href="' . e($item->url) . '">' . e($item->title) . '</a>';
            if ($item->children && $item->children->count()) {
                $html .= '<ul>';
                foreach ($item->children as $child) {
                    $html .= '<li><a href="' . e($child->url) . '">' . e($child->title) . '</a></li>';
                }
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}
