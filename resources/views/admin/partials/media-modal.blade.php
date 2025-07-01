<!-- Modal for Media Library Selection -->
<div class="modal fade" id="mediaLibraryModal" tabindex="-1" aria-labelledby="mediaLibraryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mediaLibraryModalLabel">Select Image from Media Library</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <iframe src="{{ route('admin.media.index', ['select' => 1]) }}" style="width:100%;height:70vh;border:0;" id="media-library-iframe"></iframe>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Listen for messages from the media library iframe
window.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'media-selected') {
        // event.data.media = {id, url, name, ...}
        document.getElementById('featured-image-media-id').value = event.data.media.id;
        document.getElementById('featured-image-preview').src = event.data.media.url;
        document.getElementById('featured-image-preview').classList.remove('d-none');
        // Optionally clear file input
        if(document.getElementById('featured-image-upload')) {
            document.getElementById('featured-image-upload').value = '';
        }
        // Hide modal
        var modal = bootstrap.Modal.getInstance(document.getElementById('mediaLibraryModal'));
        if(modal) modal.hide();
    }
});
</script>
@endpush
