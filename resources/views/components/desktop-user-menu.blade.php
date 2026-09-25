<div class="relative w-full" x-data="{ open: false }" {{ $attributes }}>
    {{-- Trigger Button (User Profile) --}}
    <button @click="open = !open" type="button"
        class="flex items-center justify-between w-full p-2 rounded-lg text-white hover:bg-white/10 transition-colors focus:outline-none"
        data-test="sidebar-menu-button">
        <div class="flex items-center gap-3 truncate">
            <div
                class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold text-white shrink-0">
                {{ auth()->user()->initials() }}
            </div>
            <div class="text-left truncate">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
            </div>
        </div>
        <svg class="w-4 h-4 text-white/70 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute bottom-full left-0 mb-2 w-full min-w-[200px] bg-white rounded-lg shadow-lg py-2 text-gray-800 z-50 border border-gray-100"
        style="display: none;">
        {{-- User Info Header --}}
        <div class="flex items-center gap-3 px-4 py-2 border-b border-gray-100">
            <div
                class="w-8 h-8 rounded-full bg-kb-blue-electric/10 text-kb-blue-electric flex items-center justify-center text-sm font-semibold shrink-0">
                {{ auth()->user()->initials() }}
            </div>
            <div class="grid flex-1 text-left leading-tight truncate">
                <span class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</span>
                <span class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</span>
            </div>
        </div>

        {{-- Menu Links --}}
        <div class="pt-1">
            <a href="{{ route('profile.edit') }}" wire:navigate
                class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ __('Settings') }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left"
                    data-test="logout-button">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>{{ __('Log out') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
