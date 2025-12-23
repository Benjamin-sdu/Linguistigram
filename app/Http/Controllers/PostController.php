<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function toggleLike(Post $post)
    {
        $user = auth()->user();
        $user->toggleLike($post);

        return response()->json([
            'liked' => $user->hasLiked($post),
            'likes_count' => $post->likes()->count(),
        ]);
    }
}