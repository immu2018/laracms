<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Validator;

class MenuItemController extends Controller
{
    public function store(Request $request, Menu $menu)
    {
        $type = $request->input('type');
        $data = $request->all();

        // Fill title and url for post/page before validation
        if (in_array($type, ['page', 'post'])) {
            $post = \App\Models\Post::find($request->input('related_id'));
            if ($post) {
                $data['title'] = $post->title;
                $data['url'] = $type === 'page' ? url('/pages/' . $post->slug) : url('/blog/' . $post->slug);
            }
        }

        $validated = Validator::make($data, [
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:menu_items,id',
            'type' => 'nullable|string|max:50',
            'related_id' => 'nullable|integer',
        ])->validate();
        $validated['menu_id'] = $menu->id;
        MenuItem::create($validated);
        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu item added!');
    }

    public function update(Request $request, Menu $menu, MenuItem $item)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:menu_items,id',
            'type' => 'nullable|string|max:50',
            'related_id' => 'nullable|integer',
        ]);
        $item->update($data);
        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu item updated!');
    }

    public function destroy(Menu $menu, MenuItem $item)
    {
        $item->delete();
        return redirect()->route('admin.menus.edit', $menu)->with('success', 'Menu item deleted!');
    }
}
