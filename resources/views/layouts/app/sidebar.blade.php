<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen antialiased bg-kb-white-ghost">
    {{-- Page background --}}
    <div class="fixed inset-0 -z-10 bg-kb-white-ghost"></div>

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside
            class="sticky top-0 h-screen w-64 bg-kb-blue-electric text-white flex flex-col justify-between p-4 sidebar-main hidden lg:flex">
            <div>
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" wire:navigate class="my-3 mx-auto flex items-center justify-center">
                    <img src="/kawanbisnis-white.png" alt="" class="h-16 w-auto">
                </a>

                {{-- Navigation --}}
                <nav class="mt-6">
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-wider mb-3">Menu</p>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('dashboard') }}" wire:navigate
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-white font-medium hover:bg-white/10 transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/20' : '' }}">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('action-plan.index') }}" wire:navigate
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-white font-medium hover:bg-white/10 transition-colors {{ request()->routeIs('action-plan.index') ? 'bg-white/20' : '' }}">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <span>Action Plan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('diagnosis.history') }}" wire:navigate
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-white font-medium hover:bg-white/10 transition-colors {{ request()->routeIs('diagnosis.history') || request()->routeIs('diagnosis.show') ? 'bg-white/20' : '' }}">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Riwayat Diagnosis</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('passport.index') }}" wire:navigate
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-white font-medium hover:bg-white/10 transition-colors {{ request()->routeIs('passport.index') ? 'bg-white/20' : '' }}">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4" />
                                </svg>
                                <span>Profil Bisnis</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            {{-- Desktop User Menu --}}
            <div class="pt-4 border-t border-white/10">
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            </div>
        </aside>

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Mobile Header & User Menu -->
            <header class="lg:hidden bg-kb-blue-electric text-white p-4 flex items-center justify-between">
                <button type="button" class="text-white hover:text-white/80 focus:outline-none">
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

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
