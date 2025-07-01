<?php
// Helper to render menu items as a <ol class="dd-list"> for Nestable2
if (!function_exists('buildMenuNestable')) {
    function buildMenuNestable($items) {
        $html = '<ol class="dd-list">';
        foreach ($items as $item) {
            $html .= '<li class="dd-item" data-id="'.$item->id.'">';
            $html .= '<div class="dd-handle">'.e($item->title).' <span class="text-xs text-gray-500">('.e($item->url).')</span></div>';
            if ($item->children && $item->children->count()) {
                $html .= buildMenuNestable($item->children);
            }
            $html .= '</li>';
        }
        $html .= '</ol>';
        return $html;
    }
}
