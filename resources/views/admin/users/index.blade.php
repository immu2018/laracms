@extends('layouts.admin')

@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Users</h3>
                    <div>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Add User
                        </a>
                    </div>
                </div>
                <div class="card-body border-bottom py-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(
                        (isset($errors) && $errors->any()) ||
                        session('error')
                    )
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            @if(session('error'))
                                {{ session('error') }}<br>
                            @endif
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form id="bulk-user-action-form" method="POST" action="">
                        @csrf
                        <div class="mb-2 d-flex align-items-center">
                            <select id="bulk-role-select" class="form-select form-select-sm me-2" style="width:auto;" name="role">
                                <option value="">Bulk Change Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-success btn-sm me-2" id="bulk-role-btn" disabled>Update Role</button>
                            <button type="button" class="btn btn-danger btn-sm me-2" id="bulk-delete-btn" disabled>Delete</button>
                            <span id="user-selected-count" class="text-muted"></span>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable table-responsive-md">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="user-select-all"></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td><input type="checkbox" name="ids[]" value="{{ $user->id }}" class="user-row-checkbox" @if(auth()->id() === $user->id) disabled @endif></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->roles->pluck('name')->first() ?? 'None' }}</td>
                                            <td>
                                                <div class="btn-list flex-nowrap">
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    @if(auth()->id() !== $user->id)
                                                    <form method="POST" class="d-inline single-delete-form" action="{{ route('admin.users.destroy', $user) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm single-delete-btn">Delete</button>
                                                    </form>
                                                    @else
                                                    <span class="text-muted small">(You)</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <!-- Bulk Action Modal -->
                    <div class="modal fade" id="userBulkActionModal" tabindex="-1" aria-labelledby="userBulkActionModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="userBulkActionModalLabel">Confirm Action</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body" id="userBulkActionModalBody"></div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="user-confirm-bulk-action">Continue</button>
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
    const selectAll = document.getElementById('user-select-all');
    const bulkRoleBtn = document.getElementById('bulk-role-btn');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkRoleSelect = document.getElementById('bulk-role-select');
    const selectedCount = document.getElementById('user-selected-count');
    const bulkActionModal = new bootstrap.Modal(document.getElementById('userBulkActionModal'));
    let action = '';

    function getCheckboxes() {
        return document.querySelectorAll('.user-row-checkbox');
    }
    function updateBulkState() {
        const checked = document.querySelectorAll('.user-row-checkbox:checked').length;
        bulkRoleBtn.disabled = checked === 0 || !bulkRoleSelect.value;
        bulkDeleteBtn.disabled = checked === 0;
        selectedCount.textContent = checked > 0 ? `${checked} selected` : '';
    }
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            getCheckboxes().forEach(cb => { if (!cb.disabled) cb.checked = selectAll.checked; });
            updateBulkState();
        });
    }
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-row-checkbox') || e.target === bulkRoleSelect) {
            updateBulkState();
        }
    });
    bulkRoleBtn.addEventListener('click', function() {
        action = 'role';
        document.getElementById('userBulkActionModalBody').textContent = 'Update role for selected users?';
        bulkActionModal.show();
    });
    bulkDeleteBtn.addEventListener('click', function() {
        action = 'delete';
        document.getElementById('userBulkActionModalBody').textContent = 'Delete selected users?';
        bulkActionModal.show();
    });
    document.getElementById('user-confirm-bulk-action').addEventListener('click', function() {
        let form = document.getElementById('bulk-user-action-form');
        // Remove any previous hidden ids[]
        form.querySelectorAll('input[name="ids[]"]').forEach(e => e.remove());
        // Add checked user ids as hidden inputs
        getCheckboxes().forEach(cb => {
            if (cb.checked && !cb.disabled) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
            }
        });
        // Always use POST and correct admin route
        if (action === 'role') {
            form.action = "{{ route('admin.users.bulk-role') }}";
            form.method = 'POST';
        } else if (action === 'delete') {
            form.action = "{{ route('admin.users.bulk-delete') }}";
            form.method = 'POST';
            // Remove role input if present
            let roleInput = form.querySelector('select[name=\'role\']');
            if (roleInput) roleInput.value = '';
        }
        form.submit();
    });
    updateBulkState();
});
</script>
@endpush
