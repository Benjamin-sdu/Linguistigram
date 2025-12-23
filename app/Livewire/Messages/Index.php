<?php

namespace App\Livewire\Messages;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithFileUploads;

    #[Url]
    public ?int $userId = null;
    
    #[Url]
    public ?int $conversationId = null;
    
    public string $body = '';
    public $attachment = null;
    public string $search = '';
    public bool $showCreateModal = false;

    public ?int $selectedMessageId = null;
    public ?int $editingMessageId = null;
    
    public string $editingText = '';
    public bool $showDeleteModal = false;
    public string $newMessage = '';

    protected $listeners = ['conversationCreated' => 'handleConversationCreated'];

    public function getConversationProperty(): ?Conversation
    {
        if (!$this->conversationId) {
            return null;
        }
        
        return Conversation::with(['users', 'creator'])->find($this->conversationId);
    }

    public function updatedConversationId(): void
    {
        if (!$this->conversation) return;
        
        $this->userId = null;
        
        $this->conversation->markAsReadFor(Auth::user());
        $this->cancelEdit();
        $this->cancelDelete();
    }

    public function getChatPartnerProperty(): ?User
    {
        if (!$this->userId) {
            return null;
        }
        return User::query()->find($this->userId);
    }

    public function updatedUserId(): void
    {
        if (!$this->chatPartner) return;
        
        $this->conversationId = null;

        Message::query()
            ->where('sender_id', $this->chatPartner->id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            

        $this->cancelEdit();
        $this->cancelDelete();
    }

    public function unreadCount(int $userId): int
    {
        return Message::query()
            ->where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();
    }

    public function getMessagesProperty()
    {
        if ($this->conversation) {
            return $this->conversation->messages()
                ->with('sender')
                ->orderBy('created_at')
                ->get();
        }
        
        if (!$this->chatPartner) {
            return collect();
        }

        $me = Auth::user();

        return Message::query()
            ->where(function ($q) use ($me) {
                $q->where('sender_id', $me->id)
                  ->where('receiver_id', $this->chatPartner->id);
            })
            ->orWhere(function ($q) use ($me) {
                $q->where('sender_id', $this->chatPartner->id)
                  ->where('receiver_id', $me->id);
            })
            ->orderBy('created_at')
            ->get();
    }

    public function getConversationsProperty()
    {
        return Auth::user()->conversations()
            ->with(['users', 'lastMessage.sender'])
            ->get();
    }

    public function getAllChatsProperty()
    {
        $chats = collect();
        
        $friends = $this->friends;
        $conversations = $this->conversations;
        
        foreach ($conversations as $conversation) {
            $name = $this->getConversationName($conversation);
            
            if (!empty($this->search) && !str_contains(strtolower($name), strtolower($this->search))) {
                continue;
            }
            
            $lastMessage = $conversation->lastMessage;
            
            $chats->push([
                'type' => 'conversation',
                'id' => $conversation->id,
                'name' => $name,
                'isGroup' => $conversation->isGroup(),
                'memberCount' => $conversation->users->count(),
                'lastMessageText' => $lastMessage ? $lastMessage->body : null,
                'lastMessageSender' => $lastMessage ? $lastMessage->sender->name : null,
                'unread' => $this->getConversationUnreadCount($conversation),
                'updatedAt' => $conversation->updated_at->timestamp,
            ]);
        }
        
        foreach ($friends as $friend) {
            if (!empty($this->search) && !str_contains(strtolower($friend->name), strtolower($this->search))) {
                continue;
            }
            
            $lastMessageDate = Message::query()
                ->where(function ($q) use ($friend) {
                    $q->where('sender_id', $friend->id)
                      ->where('receiver_id', Auth::id());
                })
                ->orWhere(function ($q) use ($friend) {
                    $q->where('sender_id', Auth::id())
                      ->where('receiver_id', $friend->id);
                })
                ->max('created_at');
            
            $chats->push([
                'type' => 'direct',
                'id' => $friend->id,
                'name' => $friend->name,
                'isGroup' => false,
                'flag' => $friend->getFlagPictureUrl(),
                'unread' => $this->unreadCount($friend->id),
                'updatedAt' => $lastMessageDate ? strtotime($lastMessageDate) : 0,
            ]);
        }
        
        return $chats->sortByDesc('updatedAt')->values()->toArray();
    }

    public function getConversationName($conversation): string
    {
        if ($conversation->isGroup()) {
            return $conversation->name ?? 'Unnamed Group';
        }

        $otherUser = $conversation->users->firstWhere('id', '!=', Auth::id());
        return $otherUser ? $otherUser->name : 'Unknown User';
    }

    public function getConversationUnreadCount($conversation): int
    {
        try {
            return $conversation->unreadCountForUser(Auth::user());
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getChatsForSidebar()
    {
        $chats = [];
        
        foreach ($this->friends as $friend) {
            if (!empty($this->search) && !str_contains(strtolower($friend->name), strtolower($this->search))) {
                continue;
            }
            
            $lastMessageDate = Message::query()
                ->where(function ($q) use ($friend) {
                    $q->where('sender_id', $friend->id)
                      ->where('receiver_id', Auth::id());
                })
                ->orWhere(function ($q) use ($friend) {
                    $q->where('sender_id', Auth::id())
                      ->where('receiver_id', $friend->id);
                })
                ->max('created_at');
            
            $chats[] = [
                'type' => 'direct',
                'id' => $friend->id,
                'name' => $friend->name,
                'isGroup' => false,
                'flag' => $friend->getFlagPictureUrl(),
                'unread' => $this->unreadCount($friend->id),
                'updatedAt' => $lastMessageDate ? strtotime($lastMessageDate) : 0,
            ];
        }
        
        foreach ($this->conversations as $conversation) {
            $name = $this->getConversationName($conversation);
            
            if (!empty($this->search) && !str_contains(strtolower($name), strtolower($this->search))) {
                continue;
            }
            
            $lastMessage = $conversation->lastMessage;
            
            $chats[] = [
                'type' => 'conversation',
                'id' => $conversation->id,
                'name' => $name,
                'isGroup' => $conversation->isGroup(),
                'memberCount' => $conversation->users->count(),
                'lastMessageText' => $lastMessage ? $lastMessage->body : null,
                'lastMessageSender' => $lastMessage ? $lastMessage->sender->name : null,
                'unread' => $this->getConversationUnreadCount($conversation),
                'updatedAt' => $conversation->updated_at->timestamp,
            ];
        }
        
        usort($chats, function($a, $b) {
            return $b['updatedAt'] <=> $a['updatedAt'];
        });
        
        return $chats;
    }

    public function getFriendsProperty()
    {
        $me = Auth::user();
        return $me->getConnections();
    }

    public function getFilteredFriendsProperty()
    {
        return $this->friends
            ->filter(function ($friend) {
                return empty($this->search) || 
                       str_contains(strtolower($friend->name), strtolower($this->search));
            })
            ->map(function ($friend) {
                return $this->transformFriendData($friend);
            });
    }

    public function getActiveFriendProperty()
    {
        if (!$this->userId) {
            return null;
        }
        $friend = $this->friends->firstWhere('id', $this->userId);
        return $friend ? $this->transformFriendData($friend) : null;
    }

    protected function transformFriendData($friend)
    {
        return [
            'id' => $friend->id,
            'name' => $friend->name,
            'img' => strtoupper(substr($friend->name, 0, 1)),
            'flag' => $friend->getFlagPictureUrl(),
            'unread' => $this->unreadCount($friend->id),
            'lang' => 'English',
            'messages' => $friend->id === $this->userId ? $this->messages->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'text' => $msg->body,
                    'from_me' => $msg->sender_id === auth()->id()
                ];
            })->toArray() : []
        ];
    }

    public function selectFriend(int $friendId): void
    {
        $this->userId = $friendId;
        $this->conversationId = null;
    }

    public function selectConversation(int $conversationId): void
    {
        $this->conversationId = $conversationId;
        $this->userId = null;
    }

    public function selectChat($chatType, $chatId): void
    {
        if ($chatType === 'conversation') {
            $this->selectConversation($chatId);
        } else {
            $this->selectFriend($chatId);
        }
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
        $this->userId = null;
        $this->showCreateModal = false;
    }

    public function handleSubmit(): void
    {
        if ($this->editingMessageId) {
            $this->saveEdit();
        } else {
            $this->send();
        }
    }

    public function send(): void
    {
        $this->validate([
            'newMessage' => ['required', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:png,jpg,jpeg,gif,webp,mp3,wav,ogg'],
        ]);

        if ($this->conversation) {
            $data = [
                'conversation_id' => $this->conversation->id,
                'sender_id' => Auth::id(),
                'body' => $this->newMessage,
            ];

            if ($this->attachment) {
                $path = $this->attachment->store('messages', 'public');
                $mime = $this->attachment->getClientMimeType();
                $type = str_contains($mime, 'image') ? 'image' : (str_contains($mime, 'audio') ? 'audio' : 'file');
                $data['attachment_path'] = $path;
                $data['attachment_type'] = $type;
                $data['attachment_meta'] = ['mime' => $mime, 'size' => $this->attachment->getSize()];
            }

            Message::create($data);
            
            $this->conversation->touch();

            $this->newMessage = '';
            $this->attachment = null;
            
            $this->js("window.dispatchEvent(new Event('message-sent'))");
            return;
        }

        if (!$this->chatPartner) return;

        $data = [
            'sender_id' => Auth::id(),
            'receiver_id' => $this->chatPartner->id,
            'body' => $this->newMessage,
        ];

        if ($this->attachment) {
            $path = $this->attachment->store('messages', 'public');
            $mime = $this->attachment->getClientMimeType();
            $type = str_contains($mime, 'image') ? 'image' : (str_contains($mime, 'audio') ? 'audio' : 'file');
            $data['attachment_path'] = $path;
            $data['attachment_type'] = $type;
            $data['attachment_meta'] = ['mime' => $mime, 'size' => $this->attachment->getSize()];
        }

        Message::create($data);

        $this->newMessage = '';
        $this->attachment = null;
        
        $this->js("window.dispatchEvent(new Event('message-sent'))");
    }

    public function selectMessage(int $messageId): void
    {
        $this->selectedMessageId = $messageId;
    }

    public function startEdit(int $messageId): void
    {
        $this->editingMessageId = $messageId;
        
        $message = Message::find($messageId);
        
        if ($message && $message->sender_id === Auth::id()) {
            $this->editingText = $message->body;
        }
    }

    public function saveEdit(): void
    {
        if (!$this->editingMessageId) return;
        
        $message = Message::find($this->editingMessageId);
        
        if ($message && $message->sender_id === Auth::id()) {
            $message->update(['body' => $this->editingText]);
        }
        
        $this->cancelEdit();
    }

    public function cancelEdit(): void
    {
        $this->editingMessageId = null;
        $this->editingText = '';
        $this->selectedMessageId = null;
    }

    public function refreshMessages(): void
    {
        $this->dispatch('$refresh');
    }

    public function confirmDelete(int $messageId): void
    {
        $this->selectedMessageId = $messageId;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->selectedMessageId = null;
    }

    public function deleteMessage(): void
    {
        if (!$this->selectedMessageId) return;
        
        Message::where('id', $this->selectedMessageId)
            ->where('sender_id', Auth::id()) 
            ->delete();
    
        $this->showDeleteModal = false;
        $this->selectedMessageId = null;
    }

    public function render()
    {
        return view('livewire.messages.index', [
            'activeFriend' => $this->activeFriend,
            'filteredFriends' => $this->filteredFriends,
            'allChats' => $this->getChatsForSidebar(),
            'conversation' => $this->conversation,
            'messages' => $this->messages,
        ]);
    }
}