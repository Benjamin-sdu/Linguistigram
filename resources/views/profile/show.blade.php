<x-app-layout>
    <main class="py-12" x-data="profilePage">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Top Tabs -->
            <section class="bg-base-200 p-4 rounded-lg shadow">
                <nav class="flex space-x-6" role="tablist">
                    <button @click="tab = 'profile'; profileView = 'info'"
                        :class="tab === 'profile' ? 'border-b-2 border-primary text-primary' : 'text-base-content/60'"
                        class="py-2 font-semibold focus:outline-none" role="tab">
                        Profile
                    </button>
                    @if ($user == Auth::user())
                        <button @click="tab = 'settings'; settingsView = 'cards'"
                            :class="tab === 'settings' ? 'border-b-2 border-primary text-primary' : 'text-base-content/60'"
                            class="py-2 font-semibold focus:outline-none" role="tab">
                            Settings
                        </button>
                        <button @click="tab = 'privacy'"
                            :class="tab === 'privacy' ? 'border-b-2 border-primary text-primary' : 'text-base-content/60'"
                            class="py-2 font-semibold focus:outline-none" role="tab">
                            Privacy
                        </button>
                    @endif
                </nav>
            </section>

            <!-- Tab Panels -->
            <section class="bg-base-200 p-6 rounded-lg shadow space-y-6">

                <!-- Profile Tab -->
                <div x-show="tab === 'profile'" x-cloak @edit-profile.window="profileView = 'edit'">

                    <template x-if="profileView === 'info'">
                        <div>
                            @include('profile.partials.user-info-header')

                            <section x-data="{ activeTab: 'about' }" class="mt-6">
                                <header class="border-b border-base-300 bg-base-100 rounded-t-lg">
                                    <nav class="flex space-x-8 px-6" aria-label="Profile navigation">
                                        <button @click="activeTab = 'about'"
                                            :class="activeTab === 'about' ? 'border-primary text-primary' : 'border-transparent text-base-content/60'"
                                            class="py-4 px-1 border-b-2 font-medium text-sm">
                                            About
                                        </button>
                                        <button @click="activeTab = 'followers'"
                                            :class="activeTab === 'followers' ? 'border-primary text-primary' : 'border-transparent text-base-content/60'"
                                            class="py-4 px-1 border-b-2 font-medium text-sm">
                                            Followers
                                        </button>
                                        <button @click="activeTab = 'following'"
                                            :class="activeTab === 'following' ? 'border-primary text-primary' : 'border-transparent text-base-content/60'"
                                            class="py-4 px-1 border-b-2 font-medium text-sm">
                                            Following
                                        </button>
                                    </nav>
                                </header>

                                <div class="p-6 bg-base-100 rounded-lg rounded-t-none">
                                    <section x-show="activeTab === 'about'">
                                        @include('profile.partials.about-tab-content')
                                    </section>
                                    <section x-show="activeTab === 'followers'">
                                        @include('profile.partials.followers-tab-content')
                                    </section>
                                    <section x-show="activeTab === 'following'">
                                        @include('profile.partials.following-tab-content')
                                    </section>
                                </div>
                            </section>
                        </div>
                    </template>

                    <template x-if="profileView === 'edit'">
                        <div>
                            <!-- Back Button -->
                            <button @click="profileView = 'info'" class="btn btn-sm mb-4">
                                ← Back to Profile
                            </button>

                            <!-- Profile Form -->
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </template>
                </div>


                <!-- Settings Tab -->
                <div x-show="tab === 'settings'" x-cloak>

                    <!-- Cards Grid -->
                    <div x-show="settingsView === 'cards'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <button @click="settingsView = 'appearance'"
                            class="card bg-base-100 p-6 text-center shadow rounded-lg transform transition duration-100 hover:scale-105 hover:shadow-xl active:scale-95">
                            <x-tabler-moon class="w-7 h-7 mx-auto text-primary" />
                            <p class="mt-2 font-semibold">Appearance</p>
                        </button>

                        <button @click="settingsView = 'notifications'"
                            class="card bg-base-100 p-6 text-center shadow rounded-lg transform transition duration-100 hover:scale-105 hover:shadow-xl active:scale-95">
                            <x-tabler-bell class="w-7 h-7 mx-auto text-primary" />
                            <p class="mt-2 font-semibold">Notifications</p>
                        </button>

                        <button @click="settingsView = 'blocks'"
                            class="card bg-base-100 p-6 text-center shadow rounded-lg transform transition duration-100 hover:scale-105 hover:shadow-xl active:scale-95">
                            <x-tabler-circle-minus class="w-7 h-7 mx-auto text-red-600" />
                            <p class="mt-2 font-semibold">Blocking</p>
                        </button>

                        <button @click="settingsView = 'posts'"
                            class="card bg-base-100 p-6 text-center shadow rounded-lg transform transition duration-100 hover:scale-105 hover:shadow-xl active:scale-95">
                            <x-tabler-pencil class="w-7 h-7 mx-auto text-primary" />
                            <p class="mt-2 font-semibold">Posts</p>
                        </button>

                        <button @click="settingsView = 'help'"
                            class="card bg-base-100 p-6 text-center shadow rounded-lg transform transition duration-100 hover:scale-105 hover:shadow-xl active:scale-95">
                            <x-tabler-question-mark class="w-7 h-7 mx-auto text-primary" />
                            <p class="mt-2 font-semibold">Help</p>
                        </button>
                    </div>




                    <!-- Sub Views -->
                    <div x-show="settingsView === 'appearance'">
                        <button @click="settingsView = 'cards'" class="btn btn-sm mb-4">← Back to Settings</button>
                        @include('profile.partials.appearance-settings')
                    </div>

                    <div x-show="settingsView === 'blocks'">
                        <button @click="settingsView = 'cards'" class="btn btn-sm mb-4">← Back to Settings</button>
                        @include('profile.partials.privacy-block-list')
                    </div>

                    <div x-show="settingsView === 'notifications'">
                        <button @click="settingsView = 'cards'" class="btn btn-sm mb-4">← Back to Settings</button>
                        <p class="text-sm">Notification preferences will be added here soon.</p>
                    </div>

                    <div x-show="settingsView === 'posts'">
                        <button @click="settingsView = 'cards'" class="btn btn-sm mb-4">← Back to Settings</button>
                        <p class="text-sm">Posts management is under development.</p>
                    </div>

                    <div x-show="settingsView === 'help'">
                        <button @click="settingsView = 'cards'" class="btn btn-sm mb-4">← Back to Settings</button>
                        <p class="text-sm">Need help? Contact support or check our FAQ soon.</p>
                    </div>
                </div>

                <!-- Privacy Tab -->
                <div x-show="tab === 'privacy'" x-cloak class="space-y-10">
                    @include('profile.partials.update-password-form')
                    @include('profile.partials.delete-user-form')
                </div>

            </section>
        </div>
    </main>
</x-app-layout>