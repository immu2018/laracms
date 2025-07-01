<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $type = $request->route('type') ?? $request->input('type', 'post');
        $query = Post::with(['categories', 'user'])->where('type', $type);

        // WordPress-like post visibility
        if ($user->hasRole('author') || $user->hasRole('contributor')) {
            $query->where('user_id', $user->id);
        } elseif ($user->hasRole('subscriber')) {
            // Subscribers cannot see posts list
            abort(403, 'You do not have permission to view posts.');
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('content', 'like', "%$search%")
                  ->orWhereHas('categories', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%")
                         ->orWhere('slug', 'like', "%$search%")
                         ->orWhere('description', 'like', "%$search%") ;
                  });
            });
        }
        // Filter by status if provided
        $status = $request->input('status');
        if ($status) {
            $query->where('status', $status);
        }

        $posts = $query->orderByDesc('created_at')->paginate(10);
        $types = ['post' => 'Post', 'page' => 'Page', 'news' => 'News', 'event' => 'Event'];

        // Counts for tabs (always total, not filtered)
        $allCount = Post::where('type', $type)->count();
        $publishedCount = Post::where('type', $type)->where('status', 'published')->count();
        $draftCount = Post::where('type', $type)->where('status', 'draft')->count();
        $trashCount = Post::onlyTrashed()->where('type', $type)->count();

        // Add $status to view if needed
        if ($request->ajax()) {
            return view('admin.posts.partials.table', compact('posts'))->render();
        }

        return view('admin.posts.index', compact('posts', 'type', 'status', 'allCount', 'publishedCount', 'draftCount', 'trashCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $tags = \App\Models\Tag::orderBy('name')->get();
        $type = $request->route('type') ?? 'post';
        return view('admin.posts.create', compact('categories', 'tags', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $type = $request->route('type') ?? 'post';
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'status' => 'required|in:draft,published',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|max:2048',
            'excerpt' => 'nullable|string',
            'template' => 'nullable|string',
            'password' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);
        $data = $request->only(['title', 'content', 'status', 'excerpt', 'template', 'password']);
        $data['is_featured'] = $request->has('is_featured');
        $data['type'] = $type;
        $data['user_id'] = auth()->id();
        $data['published_at'] = $request->status === 'published' ? now() : null;
        if (empty($data['excerpt'])) {
            $data['excerpt'] = str()->words(strip_tags($request->content), 55);
        }
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('featured_images', 'public');
        }
        // Handle featured image from media library
        if ($request->filled('featured_image_media_id')) {
            $media = \App\Models\Media::find($request->featured_image_media_id);
            if ($media) {
                $data['featured_image_media_id'] = $media->id;
                $data['featured_image'] = $media->path; // Optionally store path for legacy/template use
            }
        }
        $post = Post::create($data);
        // If no categories selected, assign 'Uncategorized' by default
        $categoryIds = $request->categories ?? [];
        if (empty($categoryIds)) {
            $uncat = \App\Models\Category::where('slug', 'uncategorized')->first();
            if ($uncat) {
                $categoryIds = [$uncat->id];
            }
        }
        $post->categories()->sync($categoryIds);
        $post->tags()->sync($request->tags ?? []);

        // Handle custom fields (meta) on create
        $metaKeys = $request->input('meta.key', []);
        $metaValues = $request->input('meta.value', []);
        foreach ($metaKeys as $i => $key) {
            $key = trim($key);
            if ($key === '') continue;
            $value = $metaValues[$i] ?? '';
            $post->setMeta($key, $value);
        }

        return response()->redirectToRoute($type === 'page' ? 'admin.pages.index' : 'admin.posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404); // Not implemented
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Post $post)
    {
        $categories = Category::orderBy('name')->get();
        $tags = \App\Models\Tag::orderBy('name')->get();
        $type = $request->route('type') ?? $post->type ?? 'post';
        return response()->view('admin.posts.edit', compact('post', 'categories', 'tags', 'type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $type = $request->route('type') ?? $post->type ?? 'post';
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'content' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'status' => 'required|in:draft,published',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|max:2048',
            'excerpt' => 'nullable|string',
            'template' => 'nullable|string',
            'password' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);
        $data = $request->only(['title', 'slug', 'content', 'status', 'excerpt', 'template', 'password']);
        $data['is_featured'] = $request->has('is_featured');
        $data['type'] = $type;
        $data['published_at'] = $request->status === 'published' ? now() : null;
        if (empty($data['excerpt'])) {
            $data['excerpt'] = str()->words(strip_tags($request->content), 55);
        }
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('featured_images', 'public');
        }
        // Handle featured image from media library
        if ($request->filled('featured_image_media_id')) {
            $media = \App\Models\Media::find($request->featured_image_media_id);
            if ($media) {
                $data['featured_image_media_id'] = $media->id;
                $data['featured_image'] = $media->path; // Optionally store path for legacy/template use
            }
        }
        $post->update($data);
        // If no categories selected, assign 'Uncategorized' by default
        $categoryIds = $request->categories ?? [];
        if (empty($categoryIds)) {
            $uncat = \App\Models\Category::where('slug', 'uncategorized')->first();
            if ($uncat) {
                $categoryIds = [$uncat->id];
            }
        }
        $post->categories()->sync($categoryIds);
        $post->tags()->sync($request->tags ?? []);

        // Handle custom fields (meta)
        $metaKeys = $request->input('meta.key', []);
        $metaValues = $request->input('meta.value', []);
        $existingMeta = $post->meta->keyBy('meta_key');
        $usedKeys = [];
        foreach ($metaKeys as $i => $key) {
            $key = trim($key);
            if ($key === '') continue;
            $value = $metaValues[$i] ?? '';
            $post->setMeta($key, $value);
            $usedKeys[] = $key;
        }
        // Delete removed meta
        foreach ($existingMeta as $key => $meta) {
            if (!in_array($key, $usedKeys)) {
                $meta->delete();
            }
        }

        return response()->redirectToRoute($type === 'page' ? 'admin.pages.index' : 'admin.posts.index')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        $type = $request->route('type') ?? $post->type ?? 'post';
        $post->status = 'draft';
        $post->save();
        $post->delete();
        return response()->redirectToRoute($type === 'page' ? 'admin.pages.index' : 'admin.posts.index')->with('success', 'Post deleted successfully!');
    }

    /**
     * Bulk delete posts.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            $posts = Post::whereIn('id', $ids)->get();
            foreach ($posts as $post) {
                $post->status = 'draft';
                $post->save();
                $post->delete();
            }
            return redirect()->back()->with('success', 'Selected posts deleted successfully!');
        }
        return redirect()->back()->with('error', 'No posts selected.');
    }

    /**
     * Show trashed posts.
     */
    public function trash(Request $request)
    {
        $user = auth()->user();
        $query = Post::onlyTrashed()->with(['categories', 'user']);
        if ($user->hasRole('author') || $user->hasRole('contributor')) {
            $query->where('user_id', $user->id);
        }
        $posts = $query->orderByDesc('deleted_at')->paginate(10);
        $types = ['post' => 'Post', 'page' => 'Page', 'news' => 'News', 'event' => 'Event'];
        $type = $request->input('type');
        return view('admin.posts.trash', compact('posts', 'type', 'types'));
    }

    /**
     * Restore trashed post(s).
     */
    public function restore(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        if (!empty($ids)) {
            $posts = Post::onlyTrashed()->whereIn('id', $ids)->get();
            foreach ($posts as $post) {
                $post->restore();
            }
            return redirect()->route('admin.posts.trash')->with('success', 'Selected posts restored!');
        }
        return redirect()->route('admin.posts.trash')->with('error', 'No posts selected.');
    }

    /**
     * Permanently delete trashed post(s).
     */
    public function forceDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        if (!empty($ids)) {
            Post::onlyTrashed()->whereIn('id', $ids)->forceDelete();
            return redirect()->route('admin.posts.trash')->with('success', 'Selected posts permanently deleted!');
        }
        return redirect()->route('admin.posts.trash')->with('error', 'No posts selected.');
    }

    /**
     * Duplicate a post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(Post $post)
    {
        // Duplicate post attributes except id, slug, created_at, updated_at, deleted_at
        $newPost = $post->replicate(['slug', 'created_at', 'updated_at', 'deleted_at']);
        // Generate unique title and slug
        $baseTitle = $post->title;
        $baseSlug = $post->slug;
        $i = 2;
        do {
            $newTitle = $baseTitle . ' ' . $i;
            $newSlug = \Str::slug($baseSlug . '-' . $i);
            $exists = Post::where('slug', $newSlug)->exists();
            $i++;
        } while ($exists);
        $newPost->title = $newTitle;
        $newPost->slug = $newSlug;
        $newPost->status = 'draft';
        $newPost->save();
        // Duplicate meta if needed
        if (method_exists($post, 'meta')) {
            foreach ($post->meta as $meta) {
                $newPost->meta()->create([
                    'meta_key' => $meta->meta_key,
                    'meta_value' => $meta->meta_value,
                ]);
            }
        }
        return redirect()->route('admin.posts.edit', $newPost)->with('success', 'Post duplicated!');
    }

    /**
     * Bulk update status for posts.
     */
    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');
        if (!empty($ids) && in_array($status, ['draft', 'published'])) {
            $posts = Post::whereIn('id', $ids)->get();
            foreach ($posts as $post) {
                $post->status = $status;
                $post->save();
            }
            return redirect()->back()->with('success', 'Selected posts updated to ' . $status . '!');
        }
        return redirect()->back()->with('error', 'No posts selected or invalid status.');
    }
}
