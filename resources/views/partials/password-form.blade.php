{{--
    Password Protection Form for Posts/Pages
    Include this partial when a post requires password entry
--}}
<div class="bg-white rounded-lg shadow-lg p-10 max-w-2xl w-full">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">🔒 Protected Content</h2>
    <p class="text-gray-600 mb-6">This content is password protected. Please enter the password to view.</p>
    
    @if(session('password_error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('password_error') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('post.password') }}">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input type="password" 
                   name="password" 
                   id="password" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                   required 
                   autofocus>
        </div>
        <button type="submit" 
                class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-200">
            Submit
        </button>
    </form>
</div>
