@extends('layouts.admin')

@section('content')
<div class="container-xl">
    <div class="row row-cards justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Category</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="form-control" @if($category->slug === 'uncategorized') disabled @endif>
                            @error('name')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" @if($category->slug === 'uncategorized') disabled @endif>{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image (optional)</label>
                            <input type="file" name="image" id="image" class="form-control">
                            @if($category->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image" style="max-width: 120px; max-height: 120px;">
                                </div>
                            @endif
                            @error('image')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select name="parent_id" id="parent_id" class="form-select" @if($category->slug === 'uncategorized') disabled @endif>
                                <option value="">None (Top-level)</option>
                                @foreach(App\Models\Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get() as $parent)
                                    <option value="{{ $parent->id }}" @if(old('parent_id', $category->parent_id) == $parent->id) selected @endif>{{ $parent->name }}</option>
                                    @foreach($parent->children as $child)
                                        @if($child->id !== $category->id)
                                            <option value="{{ $child->id }}" @if(old('parent_id', $category->parent_id) == $child->id) selected @endif>&nbsp;&nbsp;— {{ $child->name }}</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                            @if($category->slug !== 'uncategorized')
                                <button type="submit" class="btn btn-primary">Update</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
