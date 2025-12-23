<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;

/*
--------------------------------------------------------------------------
 Public (Guest) Routes
--------------------------------------------------------------------------
 Guests can browse posts, see connections, and view public profiles.
*/
Route::get('/', Home::class)->name('home');
Route::get('/connections', \App\Livewire\Connections\Index::class)->name('connections');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

/*
--------------------------------------------------------------------------
 Authenticated Routes
--------------------------------------------------------------------------
 Routes below are only accessible to logged-in users.
 Trying to access these while unauthenticated redirects to /login.
*/
Route::middleware('auth')->group(function () {
    Route::get('/messages', \App\Livewire\Messages\Index::class)->name('messages');
    Route::get('/swipe', \App\Livewire\Swipe\Item::class)->name('swipe');

    Route::get('/post/create', \App\Livewire\Post\Create::class)->name('post.create');
    Route::post('/posts/{post}/toggle-like', [PostController::class, 'toggleLike'])->name('post.like');
    Route::get('/posts/{post}', \App\Livewire\Post\Show::class)->name('post.show');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('post.destroy');

    Route::get('/profile', function () {
        return redirect()->route('profile.show', ['user' => auth()->user()]);
    })->name('profile.my');

    Route::post('/profile/{user}/follow', [ProfileController::class, 'follow'])->name('profile.follow');
    Route::delete('/profile/{user}/unfollow', [ProfileController::class, 'unfollow'])->name('profile.unfollow');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
