<!-- resources/views/profile/partials/appearance-settings-form.blade.php -->
<section id="appearance">
    <header>
        <h2 class="text-lg font-medium text-base-content">
            {{ __('Appearance') }}
        </h2>
        <p class="mt-1 text-sm text-base-content">
            {{ __('Choose your theme preference.') }}
        </p>
    </header>

    <div class="mt-6" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
         x-init="
            if (darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
         ">
        <x-primary-button
            class="theme-toggle inline-flex items-center px-4 py-2 rounded-md text-sm"
            @click="
                darkMode = !darkMode;
                if (darkMode) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            ">
            <span x-text="darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"></span>
        </x-primary-button>
    </div>
</section>
