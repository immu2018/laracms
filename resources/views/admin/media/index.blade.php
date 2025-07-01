@extends('layouts.admin')
@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Media Manager</h3>
                </div>
                <div class="card-body">
                    <div id="media-alert"></div>
                    <div class="col-md-6 mx-auto mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">Multiple File Upload</h3>
                                <form class="dropzone dz-clickable" id="dropzone-multiple" action="{{ route('admin.media.store') }}" autocomplete="off" novalidate>
                                    @csrf
                                    <div class="dz-default dz-message"><button class="dz-button" type="button">Drop files here to upload</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <form id="bulk-delete-form" action="{{ route('admin.media.bulk-destroy') }}" method="POST" class="mb-3 d-none">
                        @csrf
                        <input type="hidden" name="ids" id="bulk-delete-ids">
                        <button type="button" class="btn btn-danger" id="bulk-delete-btn">Delete Selected</button>
                    </form>
                    <!-- Bulk Delete Modal -->
                    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="bulkDeleteModalLabel">Confirm Bulk Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Are you sure you want to delete the selected files?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmBulkDelete">Delete</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row g-3" id="media-list">
                        @forelse($media as $file)
                            <div class="col-6 col-md-3 col-lg-2 media-item" data-id="{{ $file->id }}">
                                <div class="card h-100">
                                    <div class="form-check position-absolute m-2" style="z-index:2;">
                                        <input class="form-check-input bulk-select" type="checkbox" value="{{ $file->id }}">
                                    </div>
                                    @if(Str::startsWith($file->type, 'image/'))
                                        <img src="{{ asset('storage/' . $file->path) }}" class="card-img-top" style="object-fit:cover; height:120px;" alt="{{ $file->name }}">
                                    @else
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:120px;">
                                            <span class="text-muted">{{ strtoupper($file->type ?? 'FILE') }}</span>
                                        </div>
                                    @endif
                                    <div class="card-body p-2">
                                        <div class="small text-truncate">{{ $file->name }}</div>
                                        <div class="text-muted small">{{ number_format($file->size / 1024, 1) }} KB</div>
                                    </div>
                                    <div class="card-footer p-2 d-flex justify-content-between align-items-center">
                                        <form action="{{ route('admin.media.destroy', $file) }}" method="POST" class="delete-media-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-6 btn-outline-danger w-100 delete-media-btn">Delete</button>
                                        </form>
                                        <a href="{{ asset('storage/' . $file->path) }}" target="_blank" class="btn btn-6 btn-outline-primary w-100 ms-2">View</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted">No media files found.</div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        {{ $media->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteMediaModal" tabindex="-1" aria-labelledby="deleteMediaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteMediaModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this file?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteMedia">Delete</button>
      </div>
    </div>
  </div>
</div>

@if(request('select'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.media-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
            // Only allow image selection
            var img = item.querySelector('img.card-img-top');
            if (!img) return;
            var id = item.getAttribute('data-id');
            var url = img.getAttribute('src');
            var name = img.getAttribute('alt');
            window.parent.postMessage({
                type: 'media-selected',
                media: {id: id, url: url, name: name}
            }, '*');
        });
    });
});
</script>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css" />
<script>
Dropzone.autoDiscover = false;
const dz = new Dropzone("#dropzone-multiple", {
    url: "{{ route('admin.media.store') }}",
    paramName: "file", // Fix: use 'file' not 'file[]'
    maxFilesize: {{ config('media.max_size', 10240) / 1024 }}, // MB
    acceptedFiles: "{{ collect(config('media.allowed_types'))->map(fn($t) => '.' . $t)->implode(',') }}",
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    uploadMultiple: true,
    parallelUploads: 5,
    addRemoveLinks: true,
    successmultiple: function(files, response) {
        if(response.success && response.media) {
            let html = '';
            (Array.isArray(response.media) ? response.media : [response.media]).forEach(function(file) {
                html += `<div class=\"col-6 col-md-3 col-lg-2 media-item\" data-id=\"${file.id}\">`;
                html += `<div class=\"card h-100\">`;
                if(file.type.startsWith('image/')) {
                    html += `<img src=\"/storage/${file.path}\" class=\"card-img-top\" style=\"object-fit:cover; height:120px;\" alt=\"${file.name}\">`;
                } else {
                    html += `<div class=\"card-img-top d-flex align-items-center justify-content-center bg-light\" style=\"height:120px;\">`;
                    html += `<span class=\"text-muted\">${file.type.toUpperCase() ?? 'FILE'}</span></div>`;
                }
                html += `<div class=\"card-body p-2\">`;
                html += `<div class=\"small text-truncate\">${file.name}</div>`;
                html += `<div class=\"text-muted small\">${(file.size/1024).toFixed(1)} KB</div>`;
                html += `</div>`;
                html += `<div class=\"card-footer p-2 d-flex justify-content-between align-items-center\">`;
                html += `<form action=\"/admin/media/${file.id}\" method=\"POST\" class=\"delete-media-form d-inline\">`;
                html += `<input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token() }}\">`;
                html += `<input type=\"hidden\" name=\"_method\" value=\"DELETE\">`;
                html += `<button type=\"button\" class=\"btn btn-6 btn-outline-danger w-100 delete-media-btn\">Delete</button>`;
                html += `</form>`;
                html += `<a href=\"/storage/${file.path}\" target=\"_blank\" class=\"btn btn-6 btn-outline-primary w-100 ms-2\">View</a>`;
                html += `</div></div></div>`;
            });
            document.getElementById('media-list').insertAdjacentHTML('afterbegin', html);
            document.getElementById('media-alert').innerHTML = `<div class=\"alert alert-success\">File(s) uploaded!</div>`;
            // Re-bind delete modal events for new items
            document.querySelectorAll('.delete-media-btn').forEach(function(btn) {
                btn.onclick = function() {
                    deleteForm = this.closest('form');
                    var modal = new bootstrap.Modal(document.getElementById('deleteMediaModal'));
                    modal.show();
                };
            });
        }
    },
    error: function(file, response) {
        document.getElementById('media-alert').innerHTML = `<div class=\"alert alert-danger\">Upload failed.</div>`;
    }
});

let deleteForm = null;
document.querySelectorAll('.delete-media-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        deleteForm = this.closest('form');
        var modal = new bootstrap.Modal(document.getElementById('deleteMediaModal'));
        modal.show();
    });
});
document.getElementById('confirmDeleteMedia').addEventListener('click', function() {
    if(deleteForm) deleteForm.submit();
});


// Bulk select logic
const bulkForm = document.getElementById('bulk-delete-form');
const bulkBtn = document.getElementById('bulk-delete-btn');
const checkboxes = document.querySelectorAll('.bulk-select');
checkboxes.forEach(cb => {
    cb.addEventListener('change', function() {
        const selected = Array.from(checkboxes).filter(c => c.checked);
        bulkForm.classList.toggle('d-none', selected.length === 0);
        document.getElementById('bulk-delete-ids').value = selected.map(c => c.value).join(',');
    });
});
bulkBtn.addEventListener('click', function() {
    var modal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
    modal.show();
});
document.getElementById('confirmBulkDelete').addEventListener('click', function() {
    bulkForm.submit();
});
</script>
@endpush
@endsection
