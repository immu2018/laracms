@extends('layouts.admin')

@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Categories</h3>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Add Category
                    </a>
                </div>
                <div class="card-body border-bottom py-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                function renderCategoryTree($categories, $parentId = null, $level = 0) {
                                    foreach ($categories->where('parent_id', $parentId) as $category) {
                                        echo '<tr>';
                                        // Image
                                        echo '<td>';
                                        if ($category->image) {
                                            echo '<img src="' . asset('storage/' . $category->image) . '" style="max-width:60px;max-height:60px;">';
                                        } else {
                                            echo '<span class="text-muted small">No image</span>';
                                        }
                                        echo '</td>';
                                        // Name (indented)
                                        echo '<td>' . str_repeat('&mdash; ', $level) . e($category->name) . '</td>';
                                        // Slug, Description, Actions
                                        echo '<td>' . e($category->slug) . '</td>';
                                        echo '<td>' . e($category->description) . '</td>';
                                        echo '<td>';
                                        if ($category->slug !== 'uncategorized') {
                                            echo '<div class="btn-list flex-nowrap">';
                                            echo '<a href="' . route('admin.categories.edit', $category) . '" class="btn btn-warning btn-sm">Edit</a>';
                                            echo '<form action="' . route('admin.categories.destroy', $category) . '" method="POST" class="d-inline">';
                                            echo csrf_field() . method_field('DELETE');
                                            echo '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
                                            echo '</form></div>';
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                        // Recurse for children
                                        renderCategoryTree($categories, $category->id, $level + 1);
                                    }
                                }
                                @endphp
                                @php renderCategoryTree($categories); @endphp
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
