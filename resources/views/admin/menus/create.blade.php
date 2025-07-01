@extends('layouts.admin')
@section('content')
<div class="max-w-md mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Create Menu</h1>
    <form action="{{ route('admin.menus.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700">Location (e.g. header, footer)</label>
            <input type="text" name="location" id="location" class="w-full border rounded p-2">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
        <a href="{{ route('admin.menus.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
