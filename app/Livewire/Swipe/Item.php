<?php

namespace App\Livewire\Swipe;
use App\Models\User;

use Livewire\Component;

class Item extends Component
{
    public $users;

    public function mount()
    {
        $authUser = auth()->user();
        // Exclude users already followed
        $alreadyFollowingIds = $authUser->following()->pluck('users.id')->toArray();
        $this->users = User::where('id', '<>', $authUser->id)
            ->whereNotIn('id', $alreadyFollowingIds)
            ->get();
    }

    public function follow($userId)
    {
        $authUser = auth()->user();
        $user = User::findOrFail($userId);
        $authUser->follow($user);
        // Remove user from swipe list
        $this->users = $this->users->filter(fn($u) => $u->id !== $userId);
    }

    public function render()
    {
        return view('livewire.swipe.item', ['users' => $this->users]);
    }
}