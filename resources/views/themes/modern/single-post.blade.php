@extends('layouts.app')
@section('content')
    @include('themes.modern.partials.header')
    <div class="min-h-screen bg-gradient-to-br from-blue-100 to-purple-200 flex flex-col items-center justify-center py-10">
        @if(post_password_required($post))
            @include('partials.password-form', ['post' => $post])
        @else
            <div class="bg-white rounded-lg shadow-lg p-10 max-w-2xl w-full">
                @if(is_post_featured($post))
                    <div class="mb-4">
                        <span class="badge bg-yellow text-yellow-fg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26"></polygon>
                            </svg>
                            Featured Post
                        </span>
                    </div>
                @endif
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
                    <span>Type: {{ ucfirst(get_post_type($post)) }}</span>
                </div>
                @include('themes.modern.partials.featured-image', ['post' => $post])
                <div class="prose max-w-none text-gray-900 mb-6">
                    {!! get_post_content($post) !!}
                </div>
                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach(get_post_tags($post) as $tag)
                        <span class="bg-purple-200 text-purple-800 px-3 py-1 rounded text-xs font-semibold">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    @include('themes.modern.partials.footer')
@endsection
