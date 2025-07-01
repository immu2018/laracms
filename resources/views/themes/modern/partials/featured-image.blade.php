{{--
    Post Featured Image Example Usage
    Place this partial in your theme or include in your post/page templates.
    Usage: @include('themes.modern.partials.featured-image', ['post' => $post])
--}}
@if($post)
    @php
        $featuredImageUrl = get_post_featured_image($post);
        $media = get_post_featured_image($post, true);
    @endphp
    @if($featuredImageUrl)
        <div class="post-featured-image mb-4">
            <img src="{{ $featuredImageUrl }}" alt="{{ $media ? $media->name : ($post->title ?? 'Featured Image') }}" class="img-fluid rounded shadow">
        </div>
    @endif
@endif
