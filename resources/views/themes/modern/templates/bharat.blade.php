{{--
    Template Name: test Page
    Description: A custom landing page template.
--}}
@extends('layouts.app')
@section('content')
    @php
        $featuredPosts = get_featured_posts(['limit' => 5]);
    @endphp
    <div class="min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 flex flex-col items-center justify-center py-10">
        <div class="bg-white rounded-lg shadow-lg p-10 max-w-2xl w-full text-center mb-10">
            <h1 class="text-4xl font-extrabold mb-4 text-purple-700">Welcome to Your Landing Page!</h1>
            <p class="text-lg text-gray-800 mb-6">This is a custom landing page template. You can fully customize this layout to create beautiful, conversion-focused pages.</p>
            <a href="/" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg font-semibold shadow hover:bg-purple-700 transition">Go to Home</a>
        </div>
        
        @if($featuredPosts->count() > 0)
        <div class="w-full max-w-2xl mx-auto mb-10">
            <h2 class="text-2xl font-bold mb-4 text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2 text-yellow-300" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26"></polygon>
                </svg>
                Featured Posts
            </h2>
            <div class="flex flex-col gap-6">
                @foreach($featuredPosts as $featured)
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-400">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-xl font-bold text-purple-700">
                                <a href="{{ get_post_permalink($featured) }}" class="hover:text-purple-900">{{ $featured->title }}</a>
                            </h3>
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">
                                ⭐ Featured
                            </span>
                        </div>
                        @if($featured->excerpt)
                            <p class="text-gray-600 mb-3">{{ $featured->excerpt }}</p>
                        @endif
                        <div class="flex items-center text-sm text-gray-500">
                            <span>By {{ get_post_author($featured) }}</span>
                            <span class="mx-2">•</span>
                            <span>
                                {{ $featured->published_at ? $featured->published_at->format('M d, Y') : 'Draft' }}
                            </span>
                            <span class="mx-2">•</span>
                            <span>{{ ucfirst($featured->type) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(isset($post))
        <div class="w-full max-w-2xl mx-auto mt-10">
            <h2 class="text-2xl font-bold mb-4 text-white">This Page</h2>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-purple-700 mb-2">{{ $post->title }}</h3>
                @if($post->excerpt)
                    <p class="text-gray-600">{{ $post->excerpt }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
@endsection
