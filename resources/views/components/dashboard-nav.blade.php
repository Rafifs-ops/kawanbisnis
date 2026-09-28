@php
    $items = [
        [
            'route' => 'dashboard',
            'label' => 'Dashboard',
            'active' => request()->routeIs('dashboard'),
            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        ],
        [
            'route' => 'action-plan.index',
            'label' => 'Action Plan',
            'active' => request()->routeIs('action-plan.index'),
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
        ],
        [
            'route' => 'diagnosis.history',
            'label' => 'Riwayat Diagnosis',
            'active' => request()->routeIs('diagnosis.history') || request()->routeIs('diagnosis.show'),
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'route' => 'passport.index',
            'label' => 'Profil Bisnis',
            'active' => request()->routeIs('passport.index'),
            'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4',
        ],
    ];
@endphp

<nav class="mt-6">
    <p class="text-xs font-semibold text-white/70 uppercase tracking-wider mb-3">Menu</p>
    <ul class="space-y-1">
        @foreach ($items as $item)
            <li>
                <a href="{{ route($item['route']) }}" wire:navigate
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white font-medium hover:bg-white/10 transition-colors {{ $item['active'] ? 'bg-white/20' : '' }}">
                    <svg class="w-5 h-5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
