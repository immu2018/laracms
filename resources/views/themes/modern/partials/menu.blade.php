{{-- themes/modern/partials/menu.blade.php --}}
@php
    $items = $items ?? collect();
@endphp
<nav class="space-x-6">
    @foreach ($items as $item)
        @php
            // Determine URL based on type
            switch ($item->type) {
                case 'page':
                    $url = url('/pages/' . ($item->related_id ? optional(App\Models\Post::find($item->related_id))->slug : ''));
                    break;
                case 'post':
                    $url = url('/blog/' . ($item->related_id ? optional(App\Models\Post::find($item->related_id))->slug : ''));
                    break;
                case 'custom':
                default:
                    $url = $item->url;
                    break;
            }
        @endphp
        <a href="{{ $url }}" class="text-blue-700 hover:underline">{{ $item->title }}</a>
        {{-- Render children recursively if present --}}
        @if ($item->children && $item->children->count())
            <div class="ml-4">
                @include('themes.modern.partials.menu', ['items' => $item->children])
            </div>
        @endif
    @endforeach
</nav>
