@extends('layouts.admin')

@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Posts</h3>
                    <div>
                        <a href="{{ route('admin.posts.trash') }}" class="btn btn-outline-danger me-2">Trash</a>
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Add Post
                        </a>
                    </div>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="mb-3">
                        <a href="{{ route('admin.posts.index', array_merge(request()->except('status'), ['status' => null])) }}" class="btn btn-outline-primary btn-sm @if(request('status') === null) active @endif">All ({{ $allCount }})</a>
                        <a href="{{ route('admin.posts.index', array_merge(request()->except('status'), ['status' => 'published'])) }}" class="btn btn-outline-success btn-sm @if(request('status') === 'published') active @endif">Published ({{ $publishedCount }})</a>
                        <a href="{{ route('admin.posts.index', array_merge(request()->except('status'), ['status' => 'draft'])) }}" class="btn btn-outline-secondary btn-sm @if(request('status') === 'draft') active @endif">Draft ({{ $draftCount }})</a>
                        <a href="{{ route('admin.posts.trash') }}" class="btn btn-outline-danger btn-sm @if(request()->routeIs('admin.posts.trash')) active @endif">Trash ({{ $trashCount }})</a>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form id="quick-search-form" method="GET" action="{{ route('admin.posts.index') }}" class="row g-2 align-items-center mb-3">
                        <div class="col-auto flex-grow-1">
                            <input type="text" name="search" id="quick-search-input" value="{{ request('search') }}" placeholder="Quick search posts..." class="form-control" autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-primary">Search</button>
                        </div>
                    </form>
                    <div id="posts-table-wrapper">
                        <form id="bulk-action-form" method="POST" action="">
                            @csrf
                            <div class="mb-2 d-flex align-items-center">
                                <button type="button" class="btn btn-success btn-sm me-2" id="bulk-publish-btn" disabled>Publish</button>
                                <button type="button" class="btn btn-secondary btn-sm me-2" id="bulk-draft-btn" disabled>Mark as Draft</button>
                                <button type="button" class="btn btn-danger btn-sm me-2" id="bulk-delete-btn" disabled>Move to Trash</button>
                                <span id="selected-count" class="text-muted"></span>
                            </div>
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap datatable table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th>
                                            <th>Featured</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Author</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Link</th>
                                            <th class="sticky-actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($posts as $post)
                                            <tr>
                                                <td><input type="checkbox" name="ids[]" value="{{ $post->id }}" class="row-checkbox"></td>
                                                <td>
                                                    @if($post->is_featured)
                                                        <span class="badge bg-yellow-lt" title="Featured Post">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26"></polygon>
                                                            </svg>
                                                            Featured
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($post->featured_image)
                                                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Featured" style="max-width:40px;max-height:40px;object-fit:cover;" class="img-thumbnail">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($post->image)
                                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Image" style="max-width:40px;max-height:40px;object-fit:cover;" class="img-thumbnail">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $post->title }}</td>
                                                <td>
                                                    @if($post->categories->count())
                                                        {{ $post->categories->pluck('name')->join(', ') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $post->user->name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $post->status === 'published' ? 'green' : 'secondary' }}-lt">
                                                        {{ ucfirst($post->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($post->published_at)
                                                        {{ \Illuminate\Support\Carbon::parse($post->published_at)->format('Y-m-d H:i') }}
                                                    @else
                                                        {{ \Illuminate\Support\Carbon::parse($post->created_at)->format('Y-m-d H:i') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url($post->type === 'page' ? '/pages/'.$post->slug : ($post->type === 'news' ? '/news/'.$post->slug : ($post->type === 'event' ? '/events/'.$post->slug : '/blog/'.$post->slug))) }}" target="_blank" class="text-primary">View</a>
                                                </td>
                                                <td class="sticky-actions">
                                                    <div class="btn-list flex-nowrap">
                                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit</a>
                                                        <a href="{{ route('admin.posts.duplicate', $post) }}" class="btn btn-info btn-sm">Duplicate</a>
                                                        <form method="POST" class="d-inline single-delete-form">
                                                            @csrf
                                                            <button type="button" class="btn btn-danger btn-sm single-delete-btn" data-action="{{ route('admin.posts.destroy', $post) }}">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $posts->withQueryString()->links() }}
                            </div>
                            <div class="mt-2">
                                <p class="m-0 text-secondary">
                                    Showing <span>{{ $posts->firstItem() ?? 0 }}</span> to <span>{{ $posts->lastItem() ?? 0 }}</span> of <span>{{ $posts->total() }}</span> entries
                                </p>
                            </div>
                        </form>

                    </div>
                    <!-- Bulk Action Modal -->
                    <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="bulkActionModalLabel">Confirm Action</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body" id="bulkActionModalBody"></div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirm-bulk-action">Continue</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const bulkPublishBtn = document.getElementById('bulk-publish-btn');
    const bulkDraftBtn = document.getElementById('bulk-draft-btn');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const selectedCount = document.getElementById('selected-count');
    const bulkActionModal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    let action = '';

    function getCheckboxes() {
        return document.querySelectorAll('.row-checkbox');
    }
    function updateBulkState() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        bulkPublishBtn.disabled = checked === 0;
        bulkDraftBtn.disabled = checked === 0;
        bulkDeleteBtn.disabled = checked === 0;
        selectedCount.textContent = checked > 0 ? `${checked} selected` : '';
    }
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            getCheckboxes().forEach(cb => cb.checked = selectAll.checked);
            updateBulkState();
        });
    }
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('row-checkbox')) {
            updateBulkState();
        }
    });
    bulkPublishBtn.addEventListener('click', function() {
        action = 'publish';
        document.getElementById('bulkActionModalBody').textContent = 'Publish selected posts?';
        bulkActionModal.show();
    });
    bulkDraftBtn.addEventListener('click', function() {
        action = 'draft';
        document.getElementById('bulkActionModalBody').textContent = 'Mark selected posts as draft?';
        bulkActionModal.show();
    });
    bulkDeleteBtn.addEventListener('click', function() {
        action = 'delete';
        document.getElementById('bulkActionModalBody').textContent = 'Move selected posts to trash?';
        bulkActionModal.show();
    });
    document.getElementById('confirm-bulk-action').addEventListener('click', function() {
        let form = document.getElementById('bulk-action-form');
        // Remove any previous status or _method input
        let statusInput = form.querySelector('input[name="status"]');
        if (statusInput) statusInput.remove();
        let methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
        if (action === 'publish') {
            form.action = "{{ route('admin.posts.bulk-status') }}";
            form.method = 'POST';
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = 'published';
            form.appendChild(input);
        } else if (action === 'draft') {
            form.action = "{{ route('admin.posts.bulk-status') }}";
            form.method = 'POST';
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = 'draft';
            form.appendChild(input);
        } else if (action === 'delete') {
            form.action = "{{ route('admin.posts.bulk-destroy') }}";
            form.method = 'POST';
        }
        form.submit();
    });
    updateBulkState();
});
</script>
@endpush
