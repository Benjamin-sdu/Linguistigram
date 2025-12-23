<?php

namespace App\Livewire\GroupChat;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithFileUploads;

    #[Url]
    public ?int $conversationId = null;
    
    public string $body = '';
    public $attachment = null;
    public string $search = '';
    public bool $showCreateModal = false;

    protected $listeners = ['conversationCreated' => 'handleConversationCreated'];

    public function getConversationProperty(): ?Conversation
    {
        if (!$this->conversationId) {
            return null;
        }
        
        return Conversation::with(['users', 'creator'])
            ->find($this->conversationId);
    }

    public function updatedConversationId(): void
    {
        if (!$this->conversation) return;

        $this->conversation->markAsReadFor(Auth::user());
    }

    public function getMessagesProperty()
    {
        if (!$this->conversation) {
            return collect();
        }

        return $this->conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();
    }

    public function getConversationsProperty()
    {
        return Auth::user()->conversations()
            ->with(['users', 'lastMessage.sender'])
            ->get();
    }

    public function getFilteredConversationsProperty()
    {
        return $this->conversations->filter(function ($conversation) {
            if (empty($this->search)) {
                return true;
            }
            
            $searchLower = strtolower($this->search);
            
            if ($conversation->name && str_contains(strtolower($conversation->name), $searchLower)) {
                return true;
            }
            
            foreach ($conversation->users as $user) {
                if (str_contains(strtolower($user->name), $searchLower)) {
                    return true;
                }
            }
            
            return false;
        });
    }

    public function sendMessage(): void
    {
        $this->validate([
            'body' => 'required|string|max:5000',
        ]);

        if (!$this->conversation) {
            return;
        }

        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => Auth::id(),
            'body' => $this->body,
        ]);

        $this->conversation->touch();

        $this->body = '';
        $this->dispatch('messageSent');
    }

    public function selectConversation(int $conversationId): void
    {
        $this->conversationId = $conversationId;
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function handleConversationCreated($conversationId): void
    {
        $this->conversationId = $conversationId;
        $this->showCreateModal = false;
    }

    public function leaveConversation(): void
    {
        if (!$this->conversation) {
            return;
        }

        $this->conversation->removeUser(Auth::user());
        $this->conversationId = null;
        
        session()->flash('message', 'You have left the conversation.');
    }

    public function getConversationName($conversation): string
    {
        if ($conversation->isGroup()) {
            return $conversation->name ?? 'Unnamed Group';
        }

        $otherUser = $conversation->users->firstWhere('id', '!=', Auth::id());
        return $otherUser ? $otherUser->name : 'Unknown User';
    }

    public function getUnreadCount($conversation): int
    {
        try {
            return $conversation->unreadCountForUser(Auth::user());
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function render()
    {
        return view('livewire.group-chat.index', [
            'conversations' => $this->filteredConversations,
            'messages' => $this->messages,
            'conversation' => $this->conversation,
        ]);
    }
}
