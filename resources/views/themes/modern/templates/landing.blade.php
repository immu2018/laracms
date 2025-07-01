{{--
    Template Name: Landing Page
    Description: A custom landing page template.
--}}
@extends('layouts.app')
@section('content')
    @php
        $recentPosts = isset($posts) ? $posts : (\App\Models\Post::with(['user', 'categories', 'tags', 'meta'])
            ->orderByDesc('published_at')
            ->get());
    @endphp
    <div class="min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 flex flex-col items-center justify-center py-10">
        <div class="bg-white rounded-lg shadow-lg p-10 max-w-2xl w-full text-center mb-10">
            <h1 class="text-4xl font-extrabold mb-4 text-purple-700">Welcome to Your Landing Page!</h1>
            <p class="text-lg text-gray-800 mb-6">This is a custom landing page template. You can fully customize this layout to create beautiful, conversion-focused pages.</p>
            <a href="/" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold shadow hover:bg-purple-700 transition">Go to Home</a>
        </div>
        <div class="w-full max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">All Posts</h2>
            <p class="text-gray-800">Found {{ $recentPosts->count() }} posts.</p>
            <div class="flex flex-col gap-6">
                @foreach($recentPosts as $recent)
                    @include('partials.post-card', ['post' => $recent])
                @endforeach
            </div>
            @if($recentPosts->isEmpty())
                <p class="text-gray-800">No posts found.</p>
            @endif
        </div>
        @if(isset($post))
        <div class="w-full max-w-2xl mx-auto mt-10">
            <h2 class="text-2xl font-bold mb-4 text-gray-900">This Page</h2>
            @include('partials.post-card', ['post' => $post])
        </div>
        @endif

       
    </div>
@endsection
