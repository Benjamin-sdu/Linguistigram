<?php

namespace App\Livewire\Post;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class Show extends Component
{
    public Post $post;
    public string $body = '';
    public $parent_id = null;

    protected $rules = [
        'body' => 'required|min:1|max:500'
    ];

    function addComment()
    {
        $this->validate();

        # Create comment
        Comment::create([
            'body' => $this->body,
            'parent_id' => $this->parent_id,
            'commentable_id' => $this->post->id,
            'commentable_type' => Post::class,
            'user_id' => auth()->id(),
        ]);

        $this->reset('body', 'parent_id');

        // Refresh the post with relationships
        $this->post = $this->post->fresh(['user', 'media', 'comments.user'])->loadCount('likes', 'comments');
    }

    function setParent(Comment $comment)
    {
        $this->parent_id = $comment->id;
        $this->body = "@" . $comment->user->name . " ";
        $this->post = $this->post->fresh(['user', 'media', 'comments.user'])->loadCount('likes', 'comments');
    }

    public function mount(Post $post)
    {
        $this->post = $post->load(['user', 'media', 'comments.user'])->loadCount('likes', 'comments');
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->get();

        return view('livewire.post.show', ['comments' => $comments]);
    }

    function toggleCommentLike(Comment $comment)
    {
        abort_unless(auth()->check(), 401);

        // Prevent users from liking their own comments
        if ($comment->user_id === auth()->id()) {
            return;
        }

        auth()->user()->toggleLike($comment);

        // Refresh the post with updated relationships
        $this->post = $this->post->fresh(['user', 'media', 'comments.user'])->loadCount('likes', 'comments');
    }
}
