@extends('layouts.admin')

@section('title', 'Theme Management')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Themes</h2>
                <div class="text-muted mt-1">Manage and activate front-end themes.</div>
            </div>
        </div>
    </div>
    <div class="row row-cards">
        @foreach($themes as $theme)
            <div class="col-md-4">
                <div class="card">
                    @if(isset($theme['screenshot']))
                        <img src="{{ asset($theme['screenshot']) }}" class="card-img-top" alt="{{ $theme['name'] }} preview">
                    @endif
                    <div class="card-body">
                        <h3 class="card-title">{{ $theme['name'] }}</h3>
                        <p class="card-text">{{ $theme['description'] ?? 'No description.' }}</p>
                        @if($activeTheme === $theme['key'])
                            <span class="badge bg-success">Active</span>
                        @else
                            <form method="POST" action="{{ route('admin.themes.activate') }}">
                                @csrf
                                <input type="hidden" name="theme" value="{{ $theme['key'] }}">
                                <button type="submit" class="btn btn-primary">Activate</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
