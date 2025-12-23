<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;

class Item extends Component
{
    public Post $post;

    public function togglePostLike()
    {
        abort_unless(auth()->check(), 401);
        auth()->user()->toggleLike($this->post);
        
        // Refresh the post with updated counts
        $this->post->loadCount('likes', 'comments');
    }

    public function mount(Post $post)
    {
        $this->post = $post->loadCount('likes', 'comments');
    }

    public function render()
    {
        return view('livewire.post.item');
    }

    public function destroy()
    {
        // Force delete comments first (bypass soft delete)
        \App\Models\Comment::where('commentable_id', $this->post->id)
            ->where('commentable_type', Post::class)
            ->forceDelete();

        $postId = $this->post->id;
        $this->post->delete();
        $this->dispatch('post-deleted', $postId);
        session()->flash('status', 'Post deleted!');
    }
}