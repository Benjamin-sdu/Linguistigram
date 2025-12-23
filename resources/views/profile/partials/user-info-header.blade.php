<header class="bg-base-100 shadow-sm rounded-lg overflow-hidden">
    <div class="p-8">
        <div class="flex flex-col md:flex-row md:items-start gap-6">
            <!-- Profile Picture -->
            <div
                class="relative w-32 h-32 flex-shrink-0 flex items-center justify-center bg-primary rounded-full shadow-lg ring-4 ring-base-200">
                @if ($user->getProfilePictureUrl())
                    <img src="{{ $user->getProfilePictureUrl() }}" alt="{{ $user->name }}'s profile"
                        class="w-full h-full rounded-full shadow object-cover">
                @else
                    <span class="text-5xl font-bold text-primary-content">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                @endif

                <img src="{{ $user->getFlagPictureUrl() }}" alt="flag"
                    class="absolute bottom-1 right-1 w-8 h-8 object-cover rounded-full border-2 border-base-100 shadow-md" />
            </div>

            <!-- User Info -->
            <div class="flex-1 space-y-4">
                <div>
                    <h1 class="text-3xl font-bold text-base-content">
                        {{ $user->name }}
                    </h1>
                    <div class="flex items-center gap-2 mt-2 text-base-content/70">
                        <x-tabler-mail class="w-4 h-4" />
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="flex items-center gap-2 mt-1 text-sm text-base-content/60">
                        <x-tabler-calendar class="w-4 h-4" />
                        <time datetime="{{ $user->created_at->format('Y-m') }}">
                            {{ __('Member since') }} {{ $user->created_at->format('F Y') }}
                        </time>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center md:items-end justify-center md:justify-start text-center md:text-right"
                aria-label="Profile actions">

                <!-- Statistics -->
                <div class="flex gap-8 mb-4" aria-label="User statistics">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-base-content">0</p>
                        <p class="text-xs text-base-content/60 mt-1">Posts</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-base-content">{{ $followers->count() }}</p>
                        <p class="text-xs text-base-content/60 mt-1">Followers</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-base-content">{{ $following->count() }}</p>
                        <p class="text-xs text-base-content/60 mt-1">Following</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                @auth
                    <div class="mt-2 flex flex-col items-center md:items-end">
                        @if (auth()->id() === $user->id)
                            <button @click="$dispatch('edit-profile')" class="btn btn-primary">
                                Edit Profile
                            </button>
                        @else
                            <div class="flex space-x-3">
                                <form
                                    action="{{ $isFollowing ? route('profile.unfollow', $user) : route('profile.follow', $user) }}"
                                    method="POST">
                                    @csrf
                                    @if($isFollowing)
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-primary">Unfollow</button>
                                    @else
                                        <button type="submit" class="btn btn-outline btn-primary">Follow</button>
                                    @endif
                                </form>

                                @if ($isFollowing || $isFollowedBy)
                                    <a href="{{ route('messages') }}" class="btn btn-secondary">
                                        <x-tabler-messages class="w-5 h-5 mr-2" />
                                        Message
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endauth
            </div>

</header>