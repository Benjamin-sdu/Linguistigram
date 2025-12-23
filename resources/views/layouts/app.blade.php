<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Other Imports -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-8kH2cSWpqVZgUAljS3RhoQ6aE8SR5GqAc4D8RQE3zTXdyxQn8uEr27+PuOi6H0pb+zclZP1ZPH3u9d7D5eY4zg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex">

        <!-- Sidebar -->
        <nav class="bg-base-200 border-t sm:border-t-0 sm:border-r border-base-300 z-50 w-60" id="sidebar">
            <ul class="sidebar-menu flex flex-col h-full">

                {{-- Always Visible Links (Guests + Auth Users) --}}
                <li class="sidebar-item">
                    <a href="{{ route('home') }}" class="sidebar-link flex items-center hover:bg-base-300 p-3">
                        <x-monoicon-home class="sidebar-icon text-base-content" />
                        <span class="sidebar-text ml-3">Home</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('connections') }}" class="sidebar-link flex items-center hover:bg-base-300 p-3">
                        <x-tabler-wifi class="sidebar-icon text-base-content" />
                        <span class="sidebar-text ml-3">Connections</span>
                    </a>
                </li>

                {{-- Authenticated-Only Links --}}
                @auth
                    <li class="sidebar-item">
                        <a href="{{ route('messages') }}" class="sidebar-link flex items-center hover:bg-base-300 p-3">
                            <x-tabler-messages class="sidebar-icon text-base-content" />
                            <span class="sidebar-text ml-3">Messages</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ route('swipe') }}" class="sidebar-link flex items-center hover:bg-base-300 p-3">
                            <x-tabler-heart class="sidebar-icon text-base-content" />
                            <span class="sidebar-text ml-3">Swiper</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ route('profile.my') }}" class="sidebar-link flex items-center hover:bg-base-300 p-3">
                            <x-css-profile class="sidebar-icon text-base-content" />
                            <span class="sidebar-text ml-3">Profile</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ route('post.create') }}" class="sidebar-link flex items-center hover:bg-base-300 p-3">
                            <x-tabler-plus class="sidebar-icon text-base-content" />
                            <span class="sidebar-text ml-3">Create Post</span>
                        </a>
                    </li>
                @endauth

                {{-- You can add a footer or extra nav below --}}
                <x-more-nav />
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 bg-base-100" id="mainContent">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
