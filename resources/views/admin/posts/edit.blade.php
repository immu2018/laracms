@extends('layouts.admin')

@section('content')
<form method="POST" action="{{ route('admin.posts.update', $post->id) }}" enctype="multipart/form-data" id="post-form">
    @csrf
    @method('PUT')
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-8">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Edit Post</h3>
                            <div class="row row-cards">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Slug (URL)</label>
                                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $post->slug) }}" required>
                                        <small class="form-text text-muted">You can edit the last part of the URL. Must be unique.</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Content</label>
                                        <textarea name="content" rows="8" class="form-control" required>{{ old('content', $post->content) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Excerpt</label>
                                        <textarea name="excerpt" rows="3" class="form-control">{{ old('excerpt', $post->excerpt) }}</textarea>
                                    </div>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Post Settings</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control form-select" required>
                                    <option value="draft" @if($post->status == 'draft') selected @endif>Draft</option>
                                    <option value="published" @if($post->status == 'published') selected @endif>Published</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Categories</label>
                                {!! renderCategoryCheckboxes($categories, old('categories', $post->categories->pluck('id')->toArray())) !!}
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tags</label>
                                <select name="tags[]" class="form-control form-select" multiple>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" @if($post->tags->contains($tag->id)) selected @endif>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary w-100" id="choose-media-btn" data-bs-toggle="modal" data-bs-target="#mediaLibraryModal">Choose or Upload from Media Library</button>
                                </div>
                                <input type="hidden" name="featured_image_media_id" id="featured-image-media-id" value="{{ old('featured_image_media_id', $post->featured_image_media_id ?? '') }}">
                                @if(isset($post) && $post->featured_image)
                                    <img id="featured-image-preview" src="{{ asset('storage/' . $post->featured_image) }}" class="mt-2 img-thumbnail" style="max-width: 120px;" />
                                @else
                                    <img id="featured-image-preview" class="mt-2 img-thumbnail d-none" style="max-width: 120px;" />
                                @endif
                            </div>
                            @include('admin.partials.media-modal')
                            <div class="mb-3">
                                <label class="form-label">Template</label>
                                <select name="template" class="form-control form-select">
                                    <option value="">Default</option>
                                    @foreach(get_theme_templates() as $filename => $name)
                                        <option value="{{ $filename }}" @if(old('template', $post->template) == $filename) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <span class="form-hint">Select a custom template from the active theme.</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Protection</label>
                                <input type="password" name="password" class="form-control" value="{{ old('password', $post->password) }}" placeholder="Leave empty for public access">
                                <span class="form-hint">Enter a password to protect this content. Visitors will need to enter this password to view the content.</span>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @if(old('is_featured', $post->is_featured)) checked @endif>
                                    <label class="form-check-label" for="is_featured">
                                        <strong>Featured Post</strong>
                                    </label>
                                    <div class="form-hint">Mark this post as featured to highlight it in listings and special sections.</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Update Post</button>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Custom Fields</h3>
                        </div>
                        <div class="card-body">
                            <div id="meta-fields">
                                <table class="table mb-2">
                                    <thead>
                                        <tr>
                                            <th>Key</th>
                                            <th>Value</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $meta = old('meta.key') !== null
                                            ? array_combine((array) old('meta.key'), (array) old('meta.value'))
                                            : (isset($post->meta) && $post->meta instanceof \Illuminate\Support\Collection
                                                ? $post->meta->pluck('meta_value', 'meta_key')->toArray()
                                                : (is_array($post->meta) ? $post->meta : []));
                                    @endphp
                                    @if($meta)
                                        @foreach($meta as $key => $value)
                                            @if(is_string($key) && (is_string($value) || is_numeric($value)))
                                                <tr>
                                                    <td><input type="text" name="meta[key][]" value="{{ $key }}" class="form-control" required></td>
                                                    <td><input type="text" name="meta[value][]" value="{{ $value }}" class="form-control"></td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-meta">Remove</button></td>
                                                </tr>
                                            @elseif(is_string($key) && is_array($value))
                                                <tr>
                                                    <td><input type="text" name="meta[key][]" value="{{ $key }}" class="form-control" required></td>
                                                    <td><input type="text" name="meta[value][]" value='{{ json_encode($value) }}' class="form-control"></td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-meta">Remove</button></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <button type="button" id="add-meta" class="btn btn-sm btn-outline-primary">Add Custom Field</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ...other right column cards if needed... --}}
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('add-meta').addEventListener('click', function() {
    const tbody = document.querySelector('#meta-fields tbody');
    const row = document.createElement('tr');
    row.innerHTML = `<td><input type="text" name="meta[key][]" class="form-control" required></td>
        <td><input type="text" name="meta[value][]" class="form-control"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger remove-meta">Remove</button></td>`;
    tbody.appendChild(row);
});
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-meta')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endpush
