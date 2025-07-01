{{--
    Theme: Modern
    Template Name: Front Page
    Description: A fully custom front page for the Modern theme.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Front Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-100 to-blue-200 min-h-screen">
    @include('themes.modern.partials.header')
    <main class="max-w-4xl mx-auto py-16 px-4">
        <section class="mb-12 text-center">


@php
$posts = get_posts(['type' => 'post', 'status' => 'published']);
@endphp

@foreach ($posts as $post)
    <div>
        <h2>{{ $post->title }}</h2>
        <p>{{ get_excerpt($post->content, 30) }}</p>
        <a href="{{ url('/blog/' . $post->slug) }}">Read More</a>
    </div>
@endforeach
            <h1 class="text-5xl font-bold text-purple-800 mb-4">Welcome to the Modern Front Page</h1>
            <p class="text-lg text-gray-700 mb-6">This is a fully custom, theme-based front page. You can paste any Figma/HTML here!</p>
            <a href="/blog" class="inline-block px-8 py-3 bg-blue-700 text-white rounded-lg font-semibold shadow hover:bg-blue-900 transition">Read the Blog</a>
        </section>
        <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-blue-700 mb-2">Feature One</h2>
                <p class="text-gray-700">Highlight your first feature or service here. This is just a placeholder.</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-blue-700 mb-2">Feature Two</h2>
                <p class="text-gray-700">Add more sections, images, or anything you want. 100% custom layout.</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-blue-700 mb-2">Feature Three</h2>
                <p class="text-gray-700">Developers can paste Figma/HTML here and use any CSS/JS they like.</p>
            </div>
        </section>
    </main>
    <footer class="bg-white shadow p-6 text-center text-gray-500 mt-16">

@php
$cats = get_categories();
@endphp

<ul>
@foreach ($cats as $cat)
    <li>{{ $cat->name }}</li>
@endforeach
</ul>
       &copy; {{ date('Y') }} Modern Theme. All rights reserved.
    </footer>
</body>
</html>
