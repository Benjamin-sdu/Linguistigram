<div class="flex h-screen bg-base-200 text-base-content font-sans relative items-stretch overflow-hidden">
    <!-- Conversations Sidebar -->
    <div class="w-1/3 bg-base-100 border-r border-base-300 flex flex-col h-full">
        <div class="p-4 border-b border-base-300">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-bold">Group Chats</h2>
                <button wire:click="openCreateModal" 
                    class="btn btn-sm btn-primary">
                    <x-tabler-plus class="w-4 h-4" />
                    New Group
                </button>
            </div>
            <div class="relative">
                <x-tabler-search
                    class="text-gray-600 dark:text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 pointer-events-none" />
                <input type="text"
                    wire:model.live.debounce.300ms="search"
                    class="w-full pl-10 pr-4 py-2 border-gray-400/30 dark:border-gray-500 bg-base-100 rounded-lg focus:outline-none focus:ring-indigo-600"
                    placeholder="Search conversations..." />
            </div>
        </div>
       
        <div class="flex-1 overflow-y-auto px-4 pt-2 space-y-2">
            @forelse ($conversations as $conv)
                <div wire:key="conversation-{{ $conv->id }}"
                    wire:click="selectConversation({{ $conv->id }})"
                    class="flex items-start gap-3 p-3 bg-base-100 rounded-lg cursor-pointer shadow-sm transition duration-150 ease-out hover:bg-gray-500/10 
                    {{ $conversationId === $conv->id ? 'ring-2 ring-indigo-600' : '' }}">
                    
                    <div class="relative w-12 h-12 flex items-center justify-center text-xl bg-indigo-600 rounded-full shadow">
                        @if($conv->isGroup())
                            <x-tabler-users class="w-6 h-6 text-white" />
                        @else
                            @php
                                $otherUser = $conv->users->firstWhere('id', '!=', auth()->id());
                            @endphp
                            @if($otherUser)
                                <span>{{ substr($otherUser->name, 0, 1) }}</span>
                            @endif
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-base-content text-sm truncate">
                                {{ $this->getConversationName($conv) }}
                            </span>
                            @if($this->getUnreadCount($conv) > 0)
                                <span class="bg-indigo-600 text-white text-xs rounded-full px-2 py-1 ml-2">
                                    {{ $this->getUnreadCount($conv) }}
                                </span>
                            @endif
                        </div>
                        
                        @if($conv->isGroup())
                            <span class="text-xs text-base-content/70">
                                {{ $conv->users->count() }} members
                            </span>
                        @endif
                        
                        @if($conv->lastMessage)
                            <p class="text-xs text-base-content/70 mt-1 truncate">
                                {{ $conv->lastMessage->sender->name }}: {{ \Illuminate\Support\Str::limit($conv->lastMessage->body, 30) }}
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-base-content mt-6">
                    <p class="mb-2">No conversations yet</p>
                    <button wire:click="openCreateModal" class="text-indigo-600 hover:underline text-sm">
                        Create your first group chat
                    </button>
                </div>
            @endforelse
        </div> 
    </div> 
    
    <div class="w-2/3 flex flex-col relative h-full min-h-0">
        @if ($conversation)
            <div class="flex items-center justify-between px-6 py-4 border-b border-base-300 bg-base-100 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="relative w-10 h-10 flex items-center justify-center text-xl bg-indigo-600 rounded-full shadow">
                        @if($conversation->isGroup())
                            <x-tabler-users class="w-5 h-5 text-white" />
                        @else
                            @php
                                $otherUser = $conversation->users->firstWhere('id', '!=', auth()->id());
                            @endphp
                            @if($otherUser)
                                <span>{{ substr($otherUser->name, 0, 1) }}</span>
                            @endif
                        @endif
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-base-content">
                            {{ $this->getConversationName($conversation) }}
                        </h2>
                        <p class="text-xs text-base-content/70">
                            @if($conversation->isGroup())
                                {{ $conversation->users->count() }} members
                            @else
                                Direct Message
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    @if($conversation->isGroup() && !$conversation->isAdmin(auth()->user()))
                        <button wire:click="leaveConversation" 
                            class="text-red-500 hover:text-red-700 text-sm">
                            Leave Group
                        </button>
                    @endif
                    <x-tabler-dots-vertical class="w-5 h-5 text-base-content hover:text-base-content transition cursor-pointer" />
                </div>
            </div>

            <div wire:poll.2s class="flex-1 overflow-y-auto px-6 py-4 bg-base-100">
                <div class="flex flex-col gap-3">
                    @foreach ($messages as $message)
                        @php
                            $isFromMe = $message->sender_id === auth()->id();
                        @endphp
                        <div wire:key="message-{{ $message->id }}"
                            class="flex {{ $isFromMe ? 'justify-end' : 'justify-start' }}">
                            <div class="flex flex-col {{ $isFromMe ? 'items-end' : 'items-start' }} max-w-[70%]">
                                @if(!$isFromMe && $conversation->isGroup())
                                    <span class="text-xs text-base-content/70 mb-1 px-2">
                                        {{ $message->sender->name }}
                                    </span>
                                @endif
                                <div class="{{ $isFromMe ? 'bg-indigo-600 text-white' : 'bg-gray-500 text-white' }} 
                                    p-3 rounded-lg break-words">
                                    {{ $message->body }}
                                </div>
                                <span class="text-xs text-base-content/50 mt-1 px-2">
                                    {{ $message->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <form wire:submit.prevent="sendMessage"
                class="px-6 py-4 border-t border-base-300 bg-base-100 flex items-center gap-3">
                
                <input type="text"
                    wire:model="body"
                    class="flex-1 px-4 py-2 border border-base-300 rounded-lg focus:border-indigo-600 bg-base-100"
                    placeholder="Type a message..." 
                    autocomplete="off" />
                
                <button type="submit"
                    class="btn btn-primary">
                    <x-tabler-send class="w-5 h-5" />
                </button>
            </form>
        @else
            <div class="flex-1 flex items-center justify-center text-base-content/50">
                <div class="text-center">
                    <x-tabler-message-circle class="w-16 h-16 mx-auto mb-4 opacity-50" />
                    <p class="text-lg">Select a conversation or create a new group chat</p>
                </div>
            </div>
        @endif
    </div>

    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-base-100 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <livewire:group-chat.create-modal />
                <button wire:click="closeCreateModal" 
                    class="mt-4 btn btn-ghost w-full">
                    Cancel
                </button>
            </div>
        </div>
    @endif
</div>
