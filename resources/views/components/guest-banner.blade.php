@if(session('hide_guest_banner') !== true)
<div x-data="{ show: true }" x-show="show" x-cloak
    class="border text-white px-6 py-4 flex justify-between items-center rounded"
    style="background-color: oklch(37.9% 0.146 265.522); border-color: oklch(37.9% 0.146 265.522);">
    <div class="flex items-center gap-2">
        <x-tabler-lock class="w-5 h-5" />
        <span class="text-sm font-medium">Login or register to connect with people and see more features.</span>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('login') }}" class="btn btn-sm btn-primary">
            Login
        </a>
        <a href="{{ route('register') }}" class="btn btn-sm btn-primary">
            Register
        </a>
        <button @click="show = false" class="btn btn-sm btn-outline">
            ×
        </button>
    </div>
</div>
@endif