@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Blog</h1>
    @foreach($posts as $post)
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Featured Image" class="mb-4 max-h-60 w-full object-cover rounded">
            @endif
            <h2 class="text-2xl font-bold mb-2 text-blue-700">
                {{ $post->title }}
            </h2>
            <div class="text-gray-500 text-sm mb-2">
                By {{ $post->user->name ?? 'Unknown' }} | {{ $post->published_at ? \Illuminate\Support\Carbon::parse($post->published_at)->format('M d, Y') : '' }} | Category: {{ $post->category->name ?? '' }}
            </div>
            <div class="mb-2 text-gray-700">
                {{ $post->excerpt }}
            </div>
            <div class="mb-2">
                @foreach($post->tags as $tag)
                    <span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded mr-1">#{{ $tag->name }}</span>
                @endforeach
            </div>
            @if($post->slug)
                <a href="{{ route('blog.show', $post->slug) }}" class="text-blue-600 hover:underline font-semibold">Read More</a>
            @endif
        </div>
    @endforeach
    <div class="mt-6">{{ $posts->links() }}</div>
</div>
@endsection
