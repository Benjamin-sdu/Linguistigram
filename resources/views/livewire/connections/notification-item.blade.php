<section class="flex flex-col items-start transition duration-150 ease-out hover:bg-gray-500/10">
    <div class="border-t border-base-300 w-full"></div>
    <div class="flex justify-between w-full">
        <div class="flex">
            <div>
                <!-- Avatar -->
                <div
                    class="relative w-12 h-12 m-4 flex flex-shrink-0 items-center justify-center text-2xl bg-primary rounded-full shadow">
                    @if ($user->getProfilePictureUrl())
                        <img src="{{ $user->getProfilePictureUrl() }}" alt="{{ $user->name }}'s profile"
                            class="w-full h-full rounded-full shadow object-cover">
                    @else
                        <span class="text-xl font-bold text-primary-content">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    @endif

                    <img src="{{ $user->getFlagPictureUrl() }}" alt="flag"
                        class="absolute bottom-0 right-0 w-4 h-4 object-cover rounded-full border border-base-100 shadow" />
                </div>

                <!-- *Status (Not implemented) -->
                <div class="hidden items-center justify-center gap-1 my-1 -mt-3">
                    <div
                        class="h-1.5 w-1.5 rounded-full {{ $status == 'Online' ? 'bg-green-600' : ($status == 'Idle' ? 'bg-yellow-500' : 'bg-gray-500') }}">
                    </div>
                    <p class="font-normal text-xs text-gray-500 dark:text-gray-200/90">{{ $status }}</p>
                </div>
            </div>

            <!-- Message -->
            <div class="flex flex-col justify-between pt-4 pb-2">
                <h5>
                    <span class="font-bold">{{ $user->name }}</span> followed you.
                </h5>
                <p>
                    <span class="text-gray-500 text-sm">
                        {{ $notification->created_at->diffForHumans() }}
                    </span>
                </p>
            </div>
        </div>

        <div class="flex gap-3">
            <!-- Button for opening the user's profile -->
            <button wire:click="openProfile"
                class="flex flex-shrink-0 justify-center items-center bg-indigo-600 w-12 h-12 my-auto rounded-full transition duration-150 ease-out active:scale-90 active:bg-indigo-700">
                <x-tabler-user-up class="w-7 h-7 text-white" />
            </button>

            <!-- Button for removing the notification -->
            <button wire:click="removeNotification"
                class="flex flex-shrink-0 justify-center items-center bg-gray-400 dark:bg-base-300 w-12 h-12 my-auto mr-1 rounded-full transition duration-150 ease-out active:scale-90 active:bg-gray-500 dark:active:bg-base-200">
                <x-tabler-x class="w-6 h-6 text-white" />
            </button>
        </div>
    </div>
</section>