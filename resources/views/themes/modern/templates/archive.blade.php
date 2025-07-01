{{-- Archive Template --}}
{{-- Used for displaying a list of posts (e.g., blog, category, tag, etc.) --}}

@extends('themes.modern.partials.header')

<main class="max-w-4xl mx-auto py-16 px-4">
    <h1 class="text-3xl font-bold mb-8">Archive</h1>
    @if(isset($posts) && $posts->count())
        <div class="space-y-8">
            @foreach($posts as $post)
                <article class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold mb-2">
                        <a href="{{ url('/blog/' . $post->slug) }}">{{ $post->title }}</a>
                    </h2>
                    <p class="text-gray-700 mb-2">{{ get_excerpt($post->content, 30) }}</p>
                    <span class="text-xs text-gray-500">{{ $post->published_at }}</span>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $posts->links() }}</div>
    @else
        <p>No posts found.</p>
    @endif
</main>

@include('themes.modern.partials.footer')
