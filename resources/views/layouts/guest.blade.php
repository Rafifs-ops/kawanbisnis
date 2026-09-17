<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="text-kb-text-primary font-sans antialiased">
        {{-- Blue-dominant aurora mesh — Opsi B --}}
        <div class="fixed inset-0 -z-10" style="background: var(--gradient-kb-hero);"></div>

        <div class="relative">
            {{ $slot }}
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
