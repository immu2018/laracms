<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PasswordController extends Controller
{
    /**
     * Handle post password submission.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'password' => 'required|string',
        ]);

        $post = Post::findOrFail($request->post_id);
        
        if ($request->password === $post->password) {
            // Store correct password in session
            $sessionKey = 'post_password_' . $post->id;
            session([$sessionKey => $post->password]);
            
            // Redirect back to the post
            return redirect()->back()->with('success', 'Password accepted!');
        } else {
            // Redirect back with error
            return redirect()->back()->with('password_error', 'Incorrect password. Please try again.');
        }
    }
}
