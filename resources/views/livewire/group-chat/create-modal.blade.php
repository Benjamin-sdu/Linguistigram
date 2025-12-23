<div class="space-y-4">
    <h2 class="text-2xl font-bold text-base-content mb-4">Create Group Chat</h2>
    
    <form wire:submit.prevent="createGroupChat" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-base-content mb-1">
                Group Name *
            </label>
            <input type="text" 
                id="name"
                wire:model="name"
                class="w-full px-4 py-2 border border-base-300 rounded-lg focus:border-indigo-600 bg-base-100"
                placeholder="Enter group name" 
                required />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-base-content mb-1">
                Description (optional)
            </label>
            <textarea 
                id="description"
                wire:model="description"
                rows="3"
                class="w-full px-4 py-2 border border-base-300 rounded-lg focus:border-indigo-600 bg-base-100"
                placeholder="What's this group about?"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="avatar" class="block text-sm font-medium text-base-content mb-1">
                Group Avatar (optional)
            </label>
            <input type="file" 
                id="avatar"
                wire:model="avatar"
                accept="image/*"
                class="w-full px-4 py-2 border border-base-300 rounded-lg bg-base-100" />
            @error('avatar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            
            @if ($avatar)
                <div class="mt-2">
                    <img src="{{ $avatar->temporaryUrl() }}" 
                        class="w-20 h-20 rounded-full object-cover" 
                        alt="Preview">
                </div>
            @endif
        </div>

        <div>
            <label for="userSearch" class="block text-sm font-medium text-base-content mb-1">
                Add Members * (Select at least one)
            </label>
            <div class="relative mb-2">
                <x-tabler-search
                    class="text-gray-600 dark:text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 pointer-events-none" />
                <input type="text"
                    id="userSearch"
                    wire:model.live.debounce.300ms="userSearch"
                    class="w-full pl-10 pr-4 py-2 border border-base-300 rounded-lg focus:border-indigo-600 bg-base-100"
                    placeholder="Search your connections..." />
            </div>
            @error('selectedUsers') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="max-h-64 overflow-y-auto border border-base-300 rounded-lg p-2 space-y-1">
            @forelse($availableUsers as $user)
                <div wire:key="user-{{ $user->id }}"
                    wire:click="toggleUser({{ $user->id }})"
                    class="flex items-center gap-3 p-2 rounded cursor-pointer hover:bg-gray-500/10 transition
                    {{ in_array($user->id, $selectedUsers) ? 'bg-indigo-600/20' : '' }}">
                    
                    <div class="w-10 h-10 flex items-center justify-center text-sm bg-indigo-600 text-white rounded-full">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    
                    <div class="flex-1">
                        <p class="font-medium text-base-content">{{ $user->name }}</p>
                        <p class="text-xs text-base-content/70">{{ $user->email }}</p>
                    </div>
                    
                    @if(in_array($user->id, $selectedUsers))
                        <x-tabler-check class="w-5 h-5 text-indigo-600" />
                    @endif
                </div>
            @empty
                <div class="text-center text-base-content/70 py-4">
                    No connections found
                </div>
            @endforelse
        </div>

        @if(count($selectedUsers) > 0)
            <div class="text-sm text-base-content/70">
                {{ count($selectedUsers) }} member(s) selected
            </div>
        @endif

        <button type="submit" 
            class="w-full btn btn-primary"
            {{ count($selectedUsers) === 0 ? 'disabled' : '' }}>
            Create Group Chat
        </button>
    </form>
</div>
