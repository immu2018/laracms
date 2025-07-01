@if(isset($post) && $post)
<div class="bg-yellow-300 border-4 border-red-600 p-10 mb-8 flex flex-col md:flex-row gap-6 items-start relative">
    <div class="absolute top-2 left-2 bg-red-600 text-white px-3 py-1 rounded text-lg font-bold z-10">CARD VISIBLE</div>
    <div class="flex-shrink-0 w-full md:w-56 flex items-center justify-center">
        @if($post->featured_image)
            <a href="{{ get_post_permalink($post) }}" class="block w-full">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ get_post_title($post) }}" class="w-full h-40 object-cover rounded mb-4 md:mb-0">
            </a>
        @else
            <div class="w-full h-40 flex items-center justify-center bg-gray-200 text-gray-500 rounded mb-4 md:mb-0">
                <span class="text-sm">No Image</span>
            </div>
        @endif
    </div>
    <div class="flex-1 flex flex-col justify-between">
        <div>
            <a href="{{ get_post_permalink($post) }}" class="block hover:underline">
                <h2 class="text-2xl font-bold mb-2 text-blue-900">{{ get_post_title($post) }}</h2>
            </a>
            <p class="text-gray-900 mb-4">{{ get_post_excerpt($post) }}</p>
        </div>
        <div class="flex items-center justify-between mt-2">
            <div class="text-sm text-gray-700 flex flex-wrap gap-4">
                <span>By {{ get_post_author($post) }}</span>
                <span>
                    @if($post->published_at instanceof \Carbon\Carbon)
                        {{ $post->published_at->format('M d, Y') }}
                    @elseif(is_string($post->published_at) && strtotime($post->published_at))
                        {{ \Carbon\Carbon::parse($post->published_at)->format('M d, Y') }}
                    @else
                        {{ $post->published_at }}
                    @endif
                </span>
                <span>Type: {{ ucfirst(get_post_type($post)) }}</span>
            </div>
            <a href="{{ get_post_permalink($post) }}" class="ml-4 px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-900 transition text-sm font-semibold">Read More</a>
        </div>
    </div>
</div>
@endif
