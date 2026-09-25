<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Settings') }}" class="space-y-1">
            <flux:navlist.item :href="route('profile.edit')" wire:navigate class="rounded-xl px-3 py-2 text-kb-text-secondary hover:bg-kb-surface-2 hover:text-kb-text-primary transition-colors">{{ __('Profile') }}</flux:navlist.item>
            <flux:navlist.item :href="route('security.edit')" wire:navigate class="rounded-xl px-3 py-2 text-kb-text-secondary hover:bg-kb-surface-2 hover:text-kb-text-primary transition-colors">{{ __('Security') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading class="font-display text-2xl font-bold text-kb-text-primary">{{ $heading ?? '' }}</flux:heading>
        <flux:subheading class="text-kb-text-muted">{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-2xl">
            {{ $slot }}
        </div>
    </div>
</div>
