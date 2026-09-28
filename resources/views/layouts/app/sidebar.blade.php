<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen antialiased bg-kb-white-ghost">
    {{-- Page background --}}
    <div class="fixed inset-0 -z-10 bg-kb-white-ghost"></div>

    <div x-data="{ mobileNavOpen: false }" class="flex min-h-screen">
        {{-- Desktop Sidebar --}}
        <aside
            class="sticky top-0 h-screen w-64 bg-kb-blue-electric text-white hidden lg:flex flex-col justify-between p-4 sidebar-main">
            <div>
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" wire:navigate class="my-3 mx-auto flex items-center justify-center">
                    <img src="/kawanbisnis-white.png" alt="" class="h-16 w-auto">
                </a>

                <x-dashboard-nav />
            </div>

            {{-- Desktop User Menu --}}
            <div class="pt-4 border-t border-white/10">
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            </div>
        </aside>

        {{-- Mobile Navigation Drawer --}}
        <div x-cloak x-show="mobileNavOpen" class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
            <div x-show="mobileNavOpen" x-transition.opacity @click="mobileNavOpen = false"
                class="absolute inset-0 bg-black/50"></div>

            <aside x-show="mobileNavOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="absolute inset-y-0 left-0 w-72 max-w-[85%] overflow-y-auto bg-kb-blue-electric p-4 text-white">
                <div class="flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">
                        <img src="/kawanbisnis-white.png" alt="" class="h-12 w-auto">
                    </a>
                    <button type="button" @click="mobileNavOpen = false"
                        class="text-white/80 hover:text-white focus:outline-none" aria-label="Tutup menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <x-dashboard-nav />
            </aside>
        </div>

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Mobile Header & User Menu -->
            <header
                class="sticky top-0 z-30 lg:hidden bg-kb-blue-electric text-white p-4 flex items-center justify-between">
                <button type="button" @click="mobileNavOpen = true"
                    class="text-white hover:text-white/80 focus:outline-none" aria-label="Buka menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="flex items-center gap-2 text-white focus:outline-none">
                        <div
                            class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-semibold">
                            {{ auth()->user()->initials() }}
                        </div>
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 text-gray-800 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" wire:navigate
                            class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('Settings') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors text-left"
                                data-test="logout-button">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('Log out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
