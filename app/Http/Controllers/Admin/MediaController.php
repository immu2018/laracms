<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->hasRole('administrator')) {
            $media = Media::orderByDesc('created_at')->paginate(24);
        } else {
            $media = Media::where('uploaded_by', $user->id)->orderByDesc('created_at')->paginate(24);
        }
        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $allowedTypes = config('media.allowed_types', []);
        $maxSize = config('media.max_size', 10240); // KB
        $request->validate([
            'file' => 'required',
            'file.*' => 'file|max:' . $maxSize . '|mimes:' . implode(',', $allowedTypes),
        ]);
        $files = $request->file('file');
        if (!is_array($files)) {
            $files = array_filter([$files]); // handle single file upload
        }
        $uploaded = [];
        foreach ((array)$files as $file) {
            if (!$file) continue;
            $path = $file->store('uploads', 'public');
            $media = Media::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'uploaded_by' => Auth::id(),
            ]);
            $uploaded[] = [
                'id' => $media->id,
                'name' => $media->name,
                'path' => $media->path,
                'type' => $media->type,
                'size' => $media->size,
                'uploaded_by' => $media->uploaded_by,
            ];
        }
        return response()->json([
            'success' => true,
            'media' => $uploaded
        ]);
    }

    public function destroy(Request $request, Media $media = null)
    {
        // Single delete
        if ($media) {
            \Storage::disk('public')->delete($media->path);
            $media->delete();
            return redirect()->route('admin.media.index')->with('success', 'File deleted!');
        }
        return redirect()->route('admin.media.index')->with('error', 'No file selected.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $mediaItems = Media::whereIn('id', $ids)->get();
        foreach ($mediaItems as $item) {
            \Storage::disk('public')->delete($item->path);
            $item->delete();
        }
        return redirect()->route('admin.media.index')->with('success', 'Selected files deleted!');
    }
}
