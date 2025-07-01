@extends('layouts.admin')
@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Add to Menu</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menu-items.store', $menu) }}" method="POST" class="mb-4">
                        @csrf
                        <h4 class="mb-2">Add Page</h4>
                        <input type="hidden" name="type" value="page">
                        <select name="related_id" class="form-select mb-2">
                            @foreach(\App\Models\Post::where('type', 'page')->get() as $page)
                                <option value="{{ $page->id }}">{{ $page->title ?? 'Page #'.$page->id }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary w-100">Add Page</button>
                    </form>
                    <form action="{{ route('admin.menu-items.store', $menu) }}" method="POST" class="mb-4">
                        @csrf
                        <h4 class="mb-2">Add Post</h4>
                        <input type="hidden" name="type" value="post">
                        <select name="related_id" class="form-select mb-2">
                            @foreach(\App\Models\Post::where('type', 'post')->get() as $post)
                                <option value="{{ $post->id }}">{{ $post->title }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-outline-primary w-100">Add Post</button>
                    </form>
                    <form action="{{ route('admin.menu-items.store', $menu) }}" method="POST">
                        @csrf
                        <h4 class="mb-2">Add Custom Link</h4>
                        <input type="hidden" name="type" value="custom">
                        <input type="text" name="title" placeholder="Title" class="form-control mb-2" required>
                        <input type="text" name="url" placeholder="URL" class="form-control mb-2" required>
                        <button type="submit" class="btn btn-outline-primary w-100">Add Link</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Edit Menu</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $menu->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location (e.g. header, footer)</label>
                            <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $menu->location) }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </form>
                    <h4 class="mb-3">Menu Items</h4>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Menu Structure</h3>
                        </div>
                        <div class="card-body">
                            <div id="menu-sortable" style="border:2px dashed #007bff; padding:10px;">
                                @include('admin.menus.menu-items', ['menu' => $menu])
                            </div>
                            <button id="save-order" class="btn btn-outline-primary mt-3">Save Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('menu-sortable');
        const sortable = new Sortable(el, {
            animation: 150,
            draggable: '.sortable-item'
        });

        document.getElementById('save-order').addEventListener('click', function() {
            const order = Array.from(el.children).map(row => row.getAttribute('data-id'));
            fetch("{{ route('admin.menus.order', $menu) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ order })
            }).then(() => location.reload());
        });
    });
</script>
@endpush
@endsection
