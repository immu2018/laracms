{{-- Sidebar Navigation --}}
<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <span class="navbar-brand-text">Admin</span>
        </a>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="nav-link-title">Dashboard</span>
                    </a>
                </li>
                @role('administrator')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.posts.index') }}">
                            <span class="nav-link-title">Posts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.pages.index') }}">
                            <span class="nav-link-title">Pages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">
                            <span class="nav-link-title">Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.tags.index') }}">
                            <span class="nav-link-title">Tags</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.media.index') }}">
                            <span class="nav-link-title">Media</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.menus.index') }}">
                            <span class="nav-link-title">Menus</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <span class="nav-link-title">Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.themes.index') }}">
                            <span class="nav-link-title">Themes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.settings.index') }}">
                            <span class="nav-link-title">Settings</span>
                        </a>
                    </li>
                @elserole('editor')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.posts.index') }}">
                            <span class="nav-link-title">Posts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">
                            <span class="nav-link-title">Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.tags.index') }}">
                            <span class="nav-link-title">Tags</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.media.index') }}">
                            <span class="nav-link-title">Media</span>
                        </a>
                    </li>
                @elserole('author')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.posts.index') }}">
                            <span class="nav-link-title">Posts</span>
                        </a>
                    </li>
                @endrole
            </ul>
        </div>
    </div>
</aside>
