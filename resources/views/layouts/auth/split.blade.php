<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased">
        {{-- Calmer blue gradient for auth forms --}}
        <div class="fixed inset-0 -z-10" style="background: var(--gradient-kb-auth);"></div>

        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="relative hidden h-full flex-col p-10 text-kb-text-primary lg:flex dark:border-e dark:border-kb-border">
                {{-- Stronger blue gradient on left panel --}}
                <div class="absolute inset-0" style="background: linear-gradient(160deg, #081e8e 0%, #0f3fb0 50%, #6fc6fa 100%); opacity: 0.7;"></div>
                <div class="absolute inset-0 bg-kb-surface-solid/30"></div>

                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-md">
                        <x-app-logo-icon class="me-2 h-7" />
                    </span>
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="relative z-20 mt-auto">
                    {{-- Product-relevant visual: growth metrics preview --}}
                    <div class="mb-8 space-y-4">
                        <div class="flex items-end gap-3">
                            <span class="kpi-number text-4xl text-white">+47%</span>
                            <span class="text-sm text-white/70 mb-1">growth in 90 days</span>
                        </div>
                        <div class="h-px bg-gradient-to-r from-white/40 via-white/20 to-transparent"></div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="kpi-number text-lg text-white">4</div>
                                <div class="text-xs text-white/60">AI Agents</div>
                            </div>
                            <div>
                                <div class="kpi-number text-lg text-white">3</div>
                                <div class="text-xs text-white/60">Action Plans</div>
                            </div>
                            <div>
                                <div class="kpi-number text-lg text-white">7</div>
                                <div class="text-xs text-white/60">Day Cycle</div>
                            </div>
                        </div>
                    </div>

                    <blockquote class="space-y-2">
                        <flux:heading size="lg" class="text-white">&ldquo;Bukan sekadar planning, tapi pertumbuhan nyata.&rdquo;</flux:heading>
                        <footer><flux:heading class="text-white/60">Kawan Bisnis</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9" />
                        </span>

                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
