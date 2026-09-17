@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-24 items-center justify-center rounded-lg overflow-hidden">
            <x-app-logo-icon class="size-24" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="config('app.name', 'Kawan Bisnis')" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-lg overflow-hidden">
            <x-app-logo-icon class="size-8" />
        </x-slot>
    </flux:brand>
@endif
