<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="relative min-h-screen antialiased bg-[#081e8e]">
        {{-- Brand gradient background — Blue Light (#6fc6fa) mixed with the dark
             blues: Blue Electric (#081e8e) base and Black Blue (#0a1116) corners,
             with a White Ghost (#f5f8fc) highlight. Dark blue is now dominant. --}}
        <div class="fixed inset-0 -z-10" style="background:
            radial-gradient(ellipse at 50% 40%, rgba(111, 198, 250, 0.45) 0%, rgba(111, 198, 250, 0) 55%),
            radial-gradient(circle at 0% 0%, rgba(245, 248, 252, 0.35) 0%, rgba(245, 248, 252, 0) 45%),
            radial-gradient(circle at 100% 100%, rgba(10, 17, 22, 0.65) 0%, rgba(10, 17, 22, 0) 55%),
            linear-gradient(150deg, #6fc6fa 0%, #081e8e 42%, #0a1116 100%);"></div>

        <div class="relative flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-4">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex mb-1 items-center justify-center rounded-md">
                        <img src="/kawanbisnis-white.png" alt="" class="h-16 w-auto">
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6 rounded-3xl border border-white/40 bg-white/90 p-6 shadow-2xl backdrop-blur-md [&_[data-flux-error]]:hidden sm:p-8">
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
