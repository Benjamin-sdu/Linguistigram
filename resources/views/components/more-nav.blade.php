<li class="sidebar-item cursor-pointer mt-auto">
    <x-dropdown>
        <!-- Button -->
        <x-slot name="trigger">
            <div class="sidebar-link hover:bg-base-300">
                <x-tabler-menu class="sidebar-icon text-base-content" />
                <span class="sidebar-text">{{ __('More') }}</span>
            </div>
        </x-slot>

        <!-- Dropdown Contents -->
        <x-slot name="content">
            @if(Auth::check())
            <!-- Username -->
            <h1 class="cursor-default block w-full px-4 py-2 text-start font-semibold text-md leading-5 text-base-content">{{ Auth::user()->name }}</h1>

            <div class="border-t border-base-100 w-full"></div>

            <!-- Settings Page -->
            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Settings') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                                                                                                                                                                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
            @else
            <x-dropdown-link :href="route('login')">
                {{ __('Login') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('register')">
                {{ __('Register') }}
            </x-dropdown-link>
            @endif
        </x-slot>
    </x-dropdown>
</li>