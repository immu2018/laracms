@php
$items = isset($items) ? $items : $menu->items()->whereNull('parent_id')->orderBy('order')->get();
@endphp
@foreach($items as $item)
    <div class="sortable-item flex items-center gap-2 mb-2 bg-gray-50 p-2 rounded" data-id="{{ $item->id }}" style="background:#f8f9fa; margin-bottom:8px; padding:10px; border-radius:4px; cursor:grab;">
        @if(request('edit') == $item->id)
            <form action="{{ route('admin.menu-items.update', [$menu, $item]) }}" method="POST" class="flex gap-2 w-full">
                @csrf
                @method('PUT')
                <input type="text" name="title" value="{{ $item->title }}" class="border rounded px-2 py-1 w-1/3" required>
                <input type="text" name="url" value="{{ $item->url }}" class="border rounded px-2 py-1 w-1/3">
                <button type="submit" class="btn btn-6 btn-outline-primary active w-100">Save</button>
                <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-6 btn-outline-danger w-100">Cancel</a>
            </form>
        @else
            <span class="font-semibold">{{ $item->title }}</span>
            <span class="text-xs text-gray-500">({{ $item->url }})</span>
            <form action="{{ route('admin.menu-items.destroy', [$menu, $item]) }}" method="POST" class="inline d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-6 btn-outline-danger w-100 ms-2">Delete</button>
            </form>
            <a href="{{ route('admin.menus.edit', [$menu, 'edit' => $item->id]) }}" class="btn btn-6 btn-outline-primary active w-100 ms-2">Edit</a>
        @endif
    </div>
    @if($item->children->count())
        <div class="ml-6">
            @include('admin.menus.menu-items', ['menu' => $menu, 'items' => $item->children])
        </div>
    @endif
@endforeach
