{{-- Default Page Template --}}
@extends('layouts.app')
@section('content')
    @include('themes.modern.partials.header')
    <div class="min-h-screen bg-gradient-to-br from-blue-100 to-purple-200 flex flex-col items-center justify-center py-10">
        <div class="bg-white rounded-lg shadow-lg p-10 max-w-2xl w-full">
            <h1 class="text-4xl font-extrabold mb-4 text-purple-700">{{ get_post_title($post) }}</h1>
            <div class="flex items-center gap-4 mb-6 text-gray-600 text-sm">
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
            </div>
            @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ get_post_title($post) }}" class="w-full h-64 object-cover rounded mb-6">
            @endif
            <div class="prose max-w-none text-gray-900 mb-6">
                {!! get_post_content($post) !!}
            </div>
            <div class="flex flex-wrap gap-2 mt-4">
                @foreach(get_post_tags($post) as $tag)
                    <span class="bg-purple-200 text-purple-800 px-3 py-1 rounded text-xs font-semibold">#{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
    </div>
    @include('themes.modern.partials.footer')
@endsection
