@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6">
        @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Featured Image" class="mb-4 max-h-80 w-full object-cover rounded">
        @endif
        <h1 class="text-3xl font-bold mb-2 text-blue-700">{{ $post->title }}</h1>
        <div class="text-gray-500 text-sm mb-4">
            By {{ $post->user->name ?? 'Unknown' }} | {{ $post->published_at ? \Illuminate\Support\Carbon::parse($post->published_at)->format('M d, Y') : '' }} | Category: {{ $post->category->name ?? '' }}
        </div>
        <div class="mb-4">
            @foreach($post->tags as $tag)
                <span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded mr-1">#{{ $tag->name }}</span>
            @endforeach
        </div>
        <div class="prose max-w-none mb-6">
            {!! $post->content !!}
        </div>
        <a href="{{ route('blog.index') }}" class="text-blue-600 hover:underline">&larr; Back to Blog</a>
    </div>
</div>
@endsection
