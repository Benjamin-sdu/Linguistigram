<nav class="sidebar">
    <ul class="sidebar-menu">
        <li>
            <a href="/" class="sidebar-link">
                <x-monoicon-home class="sidebar-icon" />
                <span class="sidebar-text">Home</span>
            </a>
        </li>
        <li>
            <a href="{{ route('connect') }}" class="sidebar-link">
                <x-tabler-wifi class="sidebar-icon" />
                <span class="sidebar-text">Connect</span>
            </a>
        </li>
        <li>
            <a href="{{ route('messages') }}" class="sidebar-link">
                <x-tabler-messages class="sidebar-icon" />
                <span class="sidebar-text">Messages</span>
            </a>
        </li>
        <li>
            <a href="{{ route('profile.edit') }}" class="sidebar-link">
                <x-css-profile class="sidebar-icon" />
                <span class="sidebar-text">Profile</span>
            </a>
        </li>
    </ul>
</nav>