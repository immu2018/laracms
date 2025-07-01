<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PostObserver
{
    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post)
    {
        Log::info("Post {$post->id} was soft deleted by user " . (auth()->id() ?? 'system'));
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post)
    {
        Log::info("Post {$post->id} was restored by user " . (auth()->id() ?? 'system'));
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post)
    {
        $user = auth()->user();
        $messageText = "A new post was created: {$post->title} (ID: {$post->id}) by user " . ($user->name ?? 'system');
        $subjectText = 'New Post Created';
        \Mail::to('inteximran@gmail.com')->queue(new \App\Mail\PostNotificationMailable($messageText, $subjectText));
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post)
    {
        $user = auth()->user();
        $messageText = "A post was updated: {$post->title} (ID: {$post->id}) by user " . ($user->name ?? 'system');
        $subjectText = 'Post Updated';
        \Mail::to('inteximran@gmail.com')->queue(new \App\Mail\PostNotificationMailable($messageText, $subjectText));
    }
}
