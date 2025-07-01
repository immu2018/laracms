<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $menu = Menu::create($request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]));
        return redirect()->route('admin.menus.index')->with('success', 'Menu created!');
    }

    public function edit(Menu $menu)
    {
        $menu->load('items');
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $menu->update($request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]));
        return redirect()->route('admin.menus.index')->with('success', 'Menu updated!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted!');
    }

    public function order(Request $request, Menu $menu)
    {
        $order = $request->input('order', []);
        // $order is a flat array of IDs, e.g. [3,1,2]
        foreach ($order as $idx => $id) {
            \App\Models\MenuItem::where('id', $id)->update(['order' => $idx]);
        }
        return response()->json(['success' => true]);
    }

    public function orderNested(Request $request, Menu $menu)
    {
        $order = $request->input('order', []);
        $this->updateMenuOrder($order, null);
        return response()->json(['success' => true]);
    }

    private function updateMenuOrder($items, $parentId)
    {
        foreach ($items as $idx => $item) {
            \App\Models\MenuItem::where('id', $item['id'])->update([
                'order' => $idx,
                'parent_id' => $parentId
            ]);
            if (!empty($item['children'])) {
                $this->updateMenuOrder($item['children'], $item['id']);
            }
        }
    }
}
