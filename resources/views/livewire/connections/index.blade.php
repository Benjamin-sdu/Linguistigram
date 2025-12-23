<div>
    @guest
        @include('components.guest-banner')
    @endguest
    <div class="flex justify-center gap-4">
        <!-- Main Column -->
        <div class="flex flex-col max-w-2xl min-h-screen border-x border-base-300 bg-base-200 shadow flex-auto">
            <!-- Connections Section -->
            <section id="connectionsView">
                <header class="px-4 py-2 flex justify-between items-center">
                    <h1 class="font-bold text-2xl">Connections</h1>
                    <div>
                        @auth
                            <button id="toggleViewBtn"
                                class="lg:hidden hover:bg-gray-700/50 active:bg-gray-600/50 active:scale-90 text-gray-200 rounded-full p-2 transition relative">
                                <x-tabler-bell-filled class="text-base-content w-6 h-6" />
                                <span
                                    class="{{ $notifications->isNotEmpty() ? 'block' : 'hidden' }} absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-red-600 border border-base-100">
                                </span>
                            </button>
                        @endauth
                    </div>
                </header>

                <!-- Search Bar -->
                <div class="flex px-4 py-2">
                    <div class="relative grow">
                        <x-tabler-search
                            class="text-gray-600 dark:text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5" />
                        <input wire:model.live.debounce.100ms="search" type="search" placeholder="Search users…"
                            id="searchInput" class="w-full rounded-lg border border-gray-400/30 dark:border-gray-500 placeholder-gray-600 dark:placeholder-gray-400 bg-black/10 dark:bg-black/20 pl-10 pr-3
                           transition duration-150 ease-out
                           focus:ring-indigo-600 focus:outline-none focus:ring-2">
                    </div>
                </div>

                <!-- Suggested Heading (only when search is empty) -->
                @if(empty($search))
                    <div class="px-4 py-3 mt-3">
                        <div class="flex items-center gap-2">
                            <x-tabler-sparkles class="w-5 h-5 text-primary" />
                            <h2 class="text-base font-bold text-base-content">Suggested for you</h2>
                        </div>
                    </div>
                @endif

                <!-- Users List -->
                <ul class="pb-2" id="userList">
                    @forelse ($users as $user)
                        <li>
                            <livewire:connections.user-item :user="$user"
                                wire:key="user-{{ $user->id }}-search-{{ $search }}" />
                        </li>
                    @empty
                        <li
                            class="border-t border-base-300 w-full py-6 flex flex-col items-center justify-center text-center">
                            <x-tabler-mood-puzzled class="text-base-content w-10 h-10 text-gray-500 mb-3" />
                            <p class="text-gray-600 dark:text-gray-400 font-light">
                                No users match your search.
                            </p>
                        </li>
                    @endforelse
                </ul>
            </section>

            @auth
                <!-- Notifications Section (hidden on large screens) -->
                <section id="notificationsView" class="hidden">
                    <header class="px-4 pt-2 pb-5 flex items-center gap-2">
                        <button id="backToConnectionsBtn"
                            class="hover:bg-gray-700/50 active:bg-gray-600/50 active:scale-90 text-gray-200 rounded-full p-2 transition">
                            <x-tabler-arrow-left class="text-base-content w-6 h-6" />
                        </button>
                        <h2 class="font-bold text-2xl">Notifications</h2>
                    </header>

                    <!-- Notifications List -->
                    <ul class="pb-2">
                        @forelse ($notifications as $notification)
                            <li>
                                <livewire:connections.notification-item :notification="$notification"
                                    wire:key="notification-{{ $notification->id }}" />
                            </li>
                        @empty
                            <li
                                class="border-t border-base-300 w-full py-6 flex flex-col items-center justify-center text-center">
                                <x-tabler-mail class="text-base-content w-10 h-10 text-gray-500 mb-3" />
                                <p class="text-gray-600 dark:text-gray-400 font-light">
                                    No new notifications at the moment.
                                </p>
                            </li>
                        @endforelse
                    </ul>
                </section>
            @endauth
        </div>

        <!-- Notifications Side Column (visible on large screens) -->
        @auth
            <aside
                class="hidden lg:flex flex-col w-[35%] max-w-xs self-start mt-5 sticky top-5 border border-base-300 bg-base-200 shadow rounded-lg">
                <header class="px-4 pt-2 pb-5">
                    <h2 class="font-bold text-2xl">Notifications</h2>
                </header>
                <ul class="">
                    @forelse ($notifications as $notification)
                        <li>
                            <livewire:connections.notification-item :notification="$notification"
                                wire:key="notification-{{ $notification->id }}" />
                        </li>
                    @empty
                        <li class="border-t border-base-300 w-full py-6 flex flex-col items-center justify-center text-center">
                            <x-tabler-mail class="text-base-content w-10 h-10 text-gray-500 mb-3" />
                            <p class="text-gray-600 dark:text-gray-400 font-light">
                                No new notifications at the moment.
                            </p>
                        </li>
                    @endforelse
                </ul>
            </aside>
        @endauth
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const toggleViewBtn = document.getElementById("toggleViewBtn");
        const backBtn = document.getElementById("backToConnectionsBtn");
        const connectionsView = document.getElementById("connectionsView");
        const notificationsView = document.getElementById("notificationsView");

        let currentView = "connections";

        function showConnections() {
            if (currentView === "connections") return;
            notificationsView.classList.add("hidden");
            connectionsView.classList.remove("hidden");
            currentView = "connections";
        }

        function showNotifications() {
            if (currentView === "notifications") return;
            connectionsView.classList.add("hidden");
            notificationsView.classList.remove("hidden");
            currentView = "notifications";
        }

        toggleViewBtn?.addEventListener("click", showNotifications);
        backBtn?.addEventListener("click", showConnections);

        window.addEventListener("resize", () => {
            const isDesktop = window.innerWidth >= 1024;
            if (isDesktop && currentView === "notifications") {
                showConnections();
            }
        });
    });
</script>