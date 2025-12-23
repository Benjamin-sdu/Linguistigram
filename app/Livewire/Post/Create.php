<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $media = [];
    public $newMedia = [];
    public $description;

    public function updatedNewMedia()
    {
        // Append new files to existing media array
        $this->media = array_merge($this->media, $this->newMedia);
        $this->reset('newMedia');
    }

    public function removeMedia($index)
    {
        array_splice($this->media, $index, 1);
    }

    public function save()
    {
        $this->validate([
            'media.*' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,avi,mov|max:100000', // Max 100MB per file
        ]);

        $post = Post::create([
            'user_id' => auth()->user()->id,
            'description' => $this->description,
        ]);

        foreach ($this->media as $key => $media) {
            #get mime type
            $mime = $this->getMime(media: $media);

            #save to storage
            $path = $media->store('media', 'public');

            $url = url(path: Storage::url(path: $path));

            #create media
            Media::create([
                'url' => $url,
                'mime' => $mime,
                'mediable_id' => $post->id,
                'mediable_type' => Post::class,
            ]);
        }
        
        $this->reset();
        $this->dispatch('post-created', $post->id);

        session()->flash('message', 'Post created successfully!');
        return redirect()->route('home');
    }

    function getMime($media): string
    {
        if (str()->contains($media->getMimeType(), 'video')) {
            return 'video';
        } else {
            return 'image';
        }
    }

    public function render()
    {
        return view('livewire.post.create');
    }
}