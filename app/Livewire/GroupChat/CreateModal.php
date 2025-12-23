<?php

namespace App\Livewire\GroupChat;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CreateModal extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $description = '';
    public array $selectedUsers = [];
    public string $userSearch = '';
    public $avatar = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'selectedUsers' => 'required|array|min:1',
        'avatar' => 'nullable|image|max:2048',
    ];

    public function getAvailableUsersProperty()
    {
        $connections = Auth::user()->getConnections();
        
        if (empty($this->userSearch)) {
            return $connections;
        }
        
        return $connections->filter(function ($user) {
            return str_contains(strtolower($user->name), strtolower($this->userSearch));
        });
    }

    public function toggleUser(int $userId): void
    {
        if (in_array($userId, $this->selectedUsers)) {
            $this->selectedUsers = array_values(array_filter($this->selectedUsers, fn($id) => $id !== $userId));
        } else {
            $this->selectedUsers[] = $userId;
        }
    }

    public function createGroupChat(): void
    {
        $this->validate();

        $avatarPath = null;
        if ($this->avatar) {
            $avatarPath = $this->avatar->store('group-avatars', 'public');
        }

        $conversation = Conversation::create([
            'name' => $this->name,
            'type' => Conversation::TYPE_GROUP,
            'creator_id' => Auth::id(),
            'description' => $this->description,
            'avatar_path' => $avatarPath,
        ]);

        $conversation->addUser(Auth::user(), 'admin');

        foreach ($this->selectedUsers as $userId) {
            $user = User::find($userId);
            if ($user) {
                $conversation->addUser($user, 'member');
            }
        }

        $this->dispatch('conversationCreated', conversationId: $conversation->id);
        
        $this->reset(['name', 'description', 'selectedUsers', 'avatar', 'userSearch']);
    }

    public function render()
    {
        return view('livewire.group-chat.create-modal', [
            'availableUsers' => $this->availableUsers,
        ]);
    }
}
