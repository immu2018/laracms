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
                <th>Type</th>
                <th>Date</th>
                <th>Link</th>
                <th>Actions</th>
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
                    <td>
                        <div class="btn-list flex-nowrap">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('admin.posts.duplicate', $post) }}" class="btn btn-info btn-sm">Duplicate</a>
                            <form method="POST" class="d-inline single-delete-form">
                                @csrf
                                @method('DELETE')
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
