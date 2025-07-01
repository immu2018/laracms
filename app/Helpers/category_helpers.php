<?php
// Helper to render nested categories as checkboxes for posts

use App\Models\Category;

if (!function_exists('renderCategoryCheckboxes')) {
    function renderCategoryCheckboxes($categories, $selected = [], $parentId = null, $level = 0) {
        $html = '';
        foreach ($categories->where('parent_id', $parentId) as $category) {
            $checked = in_array($category->id, $selected) ? 'checked' : '';
            $html .= '<div style="margin-left:'.($level*18).'px">';
            $html .= '<label><input type="checkbox" name="categories[]" value="'.$category->id.'" '.$checked.'> '.$category->name.'</label>';
            $html .= '</div>';
            $html .= renderCategoryCheckboxes($categories, $selected, $category->id, $level+1);
        }
        return $html;
    }
}
