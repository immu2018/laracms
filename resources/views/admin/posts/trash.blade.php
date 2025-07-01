@extends('layouts.admin')

@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Trashed Posts</h3>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Back to Posts</a>
                </div>
                <div class="card-body border-bottom py-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="alert alert-info">Total trashed posts: {{ $posts->total() }}</div>
                    <form id="bulk-action-form" method="POST" action="">
                        @csrf
                        <div class="mb-2 d-flex align-items-center">
                            <button type="button" class="btn btn-success btn-sm me-2" id="bulk-restore-btn" disabled>Restore</button>
                            <button type="button" class="btn btn-danger btn-sm me-2" id="bulk-delete-btn" disabled>Delete Permanently</button>
                            <button type="button" class="btn btn-primary btn-sm me-2" id="bulk-publish-btn" disabled>Publish</button>
                            <button type="button" class="btn btn-secondary btn-sm me-2" id="bulk-draft-btn" disabled>Mark as Draft</button>
                            <span id="selected-count" class="text-muted"></span>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Deleted At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                        <tr>
                                            <td><input type="checkbox" name="ids[]" value="{{ $post->id }}" class="row-checkbox"></td>
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
                                            <td>{{ ucfirst($post->type) }}</td>
                                            <td>{{ $post->deleted_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $posts->withQueryString()->links() }}
                        </div>
                    </form>
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
    const bulkRestoreBtn = document.getElementById('bulk-restore-btn');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkPublishBtn = document.getElementById('bulk-publish-btn');
    const bulkDraftBtn = document.getElementById('bulk-draft-btn');
    const selectedCount = document.getElementById('selected-count');
    const bulkActionModal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    let action = '';

    function getCheckboxes() {
        return document.querySelectorAll('.row-checkbox');
    }
    function updateBulkState() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        bulkRestoreBtn.disabled = checked === 0;
        bulkDeleteBtn.disabled = checked === 0;
        bulkPublishBtn.disabled = checked === 0;
        bulkDraftBtn.disabled = checked === 0;
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
    bulkRestoreBtn.addEventListener('click', function() {
        action = 'restore';
        document.getElementById('bulkActionModalBody').textContent = 'Restore selected posts?';
        bulkActionModal.show();
    });
    bulkDeleteBtn.addEventListener('click', function() {
        action = 'delete';
        document.getElementById('bulkActionModalBody').textContent = 'Permanently delete selected posts? This cannot be undone!';
        bulkActionModal.show();
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
    document.getElementById('confirm-bulk-action').addEventListener('click', function() {
        let form = document.getElementById('bulk-action-form');
        // Remove any previous status or _method input
        let statusInput = form.querySelector('input[name="status"]');
        if (statusInput) statusInput.remove();
        let methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();
        if (action === 'restore') {
            form.action = "{{ route('admin.posts.restore') }}";
            form.method = 'POST';
        } else if (action === 'delete') {
            form.action = "{{ route('admin.posts.force-destroy') }}";
            form.method = 'POST';
        } else if (action === 'publish') {
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
        }
        form.submit();
    });
    updateBulkState();
});
</script>
@endpush
