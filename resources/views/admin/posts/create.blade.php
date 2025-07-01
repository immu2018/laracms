@extends('layouts.admin')

@section('content')
<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" id="post-form">
    @csrf
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
                            <h3 class="card-title">Add Post</h3>
                            <div class="row row-cards">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Content</label>
                                        <textarea name="content" rows="8" class="form-control" required>{{ old('content') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Excerpt</label>
                                        <textarea name="excerpt" rows="3" class="form-control">{{ old('excerpt') }}</textarea>
                                        <span class="form-hint">If left blank, an excerpt will be auto-generated from the content.</span>
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
                                    <option value="draft" @if(old('status') == 'draft') selected @endif>Draft</option>
                                    <option value="published" @if(old('status') == 'published') selected @endif>Published</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Categories</label>
                                {!! renderCategoryCheckboxes($categories, old('categories', [])) !!}
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tags</label>
                                <select name="tags[]" class="form-control form-select" multiple>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" @if(collect(old('tags'))->contains($tag->id)) selected @endif>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary w-100" id="choose-media-btn" data-bs-toggle="modal" data-bs-target="#mediaLibraryModal">Choose or Upload from Media Library</button>
                                </div>
                                <input type="hidden" name="featured_image_media_id" id="featured-image-media-id">
                                <img id="featured-image-preview" class="mt-2 img-thumbnail d-none" style="max-width: 120px;" />
                            </div>
                            @include('admin.partials.media-modal')
                            <div class="mb-3">
                                <label class="form-label">Template</label>
                                <select name="template" class="form-control form-select">
                                    <option value="">Default</option>
                                    @foreach(get_theme_templates() as $filename => $name)
                                        <option value="{{ $filename }}" @if(old('template') == $filename) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <span class="form-hint">Select a custom template from the active theme.</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Protection</label>
                                <input type="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="Leave empty for public access">
                                <span class="form-hint">Enter a password to protect this content. Visitors will need to enter this password to view the content.</span>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @if(old('is_featured')) checked @endif>
                                    <label class="form-check-label" for="is_featured">
                                        <strong>Featured Post</strong>
                                    </label>
                                    <div class="form-hint">Mark this post as featured to highlight it in listings and special sections.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h4 class="mb-2">Custom Fields</h4>
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
                                        @if(old('meta.key'))
                                            @foreach(old('meta.key') as $i => $key)
                                                <tr>
                                                    <td><input type="text" name="meta[key][]" value="{{ $key }}" class="form-control" required></td>
                                                    <td><input type="text" name="meta[value][]" value="{{ old('meta.value.'.$i) }}" class="form-control"></td>
                                                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-meta">Remove</button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <button type="button" id="add-meta" class="btn btn-sm btn-outline-primary">Add Custom Field</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Create Post</button>
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
<!-- TinyMCE (Open Source, no API key, self-hosted) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        menubar: false,
        plugins: 'lists link',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link',
        branding: false,
        height: 250
    });

function previewFeaturedImage(event) {
    const input = event.target;
    const preview = document.getElementById('featured-image-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '';
        preview.classList.add('hidden');
    }
}

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

// Media Library integration
document.getElementById('choose-media-btn').addEventListener('click', function() {
    const mediaId = document.getElementById('featured-image-media-id').value;
    // If you have a function to set the media ID in the hidden input, call it here
    // For example: setMediaId(mediaId);
});

function setMediaId(mediaId) {
    document.getElementById('featured-image-media-id').value = mediaId;
    // Optionally, you can also update the image preview if needed
    // For example: updateImagePreview(mediaId);
}
</script>
@endpush
