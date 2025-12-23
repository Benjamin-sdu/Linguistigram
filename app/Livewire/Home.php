<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Post;

class Home extends Component
{
    public $posts;

    #[On('post-created')]
    function postCreated($id)
    {
        $post=Post::find($id);

        $this->posts= $this->posts->prepend($post);
    }

    function mount() {
        $this->posts = Post::latest()->get();
    }

   public function render()
    {
        // $posts = Post::with(['user', 'media'])
        //     ->latest()
        //     ->get();

        return view('livewire.home');
    }

    #[On('post-deleted')]
    function postDeleted($id)
    {
        $this->posts = $this->posts->filter(fn($post) => $post->id !== $id);
    }
}
