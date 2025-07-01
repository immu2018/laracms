<?php
use App\Models\Menu;

if (!function_exists('get_menu_items')) {
    /**
     * Get menu items for a given location (e.g. 'header', 'footer').
     * Returns a nested array of menu items.
     */
    function get_menu_items($location)
    {
        $menu = Menu::where('location', $location)->first();
        if (!$menu) return collect();
        $items = $menu->items()->whereNull('parent_id')->get();
        // Optionally, eager load children for nested menus
        $items->load('children');
        return $items;
    }
}
