<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-kb-text-primary">Action Plan</h1>
            <flux:text class="text-kb-text-muted">Riwayat rencana aksi berdasarkan diagnosis pertumbuhan.</flux:text>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-kb-blue-electric hover:underline">
            Kembali
        </a>
    </div>

    @if (count($groupedPlans) > 0)
        <div class="space-y-8">
            @foreach ($groupedPlans as $group)
                <div>
                    {{-- Diagnosis Header --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-kb-blue-electric to-kb-blue-light text-white flex items-center justify-center text-sm font-bold shrink-0">
                            {{ $group['number'] }}
                        </div>
                        <div>
                            <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Diagnosis ke-{{ $group['number'] }}</flux:heading>
                            <flux:text class="text-xs text-kb-text-muted">{{ $group['created_at'] }}</flux:text>
                        </div>
                    </div>

                    {{-- Action Plans in this Diagnosis --}}
                    <div class="space-y-4 ml-0 md:ml-11">
                        @foreach ($group['plans'] as $plan)
                            <flux:card class="rounded-2xl border-kb-border bg-kb-surface-1 overflow-hidden">
                                {{-- Plan Header --}}
                                <div class="p-6 pb-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-kb-blue-electric to-kb-blue-light text-white flex items-center justify-center text-lg font-bold shrink-0">
                                                {{ $plan->priority_rank }}
                                            </div>
                                            <div>
                                                <flux:heading level="3" class="font-display text-lg font-semibold text-kb-text-primary">{{ $plan->title }}</flux:heading>
                                                <flux:text class="text-kb-text-secondary mt-1">{{ $plan->description }}</flux:text>
                                                <div class="flex gap-3 mt-3">
                                                    <flux:badge variant="soft" color="blue">{{ $plan->timeline_days }} hari</flux:badge>
                                                    @if (isset($plan->target_kpi['metric']))
                                                        <flux:badge variant="soft">KPI: {{ $plan->target_kpi['metric'] }}</flux:badge>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('check-in.create', $plan->id) }}" class="shrink-0">
                                            <flux:button variant="subtle" size="xs">
                                                <flux:icon name="check-circle" class="w-4 h-4 mr-1" />
                                                Tandai Selesai
                                            </flux:button>
                                        </a>
                                    </div>
                                </div>

                                {{-- Steps Timeline --}}
                                @if ($plan->steps && count($plan->steps) > 0)
                                    <div class="px-6 pb-6" x-data="{ open: false }">
                                        <div class="border-t border-kb-border-subtle pt-4">
                                            <button type="button"
                                                class="flex w-full items-center justify-between gap-2 text-left group"
                                                x-on:click="open = !open"
                                                x-bind:aria-expanded="open">
                                                <span class="text-xs font-semibold text-kb-text-muted uppercase tracking-wider group-hover:text-kb-text-secondary">
                                                    Lihat Action Plan
                                                </span>
                                                <flux:icon name="chevron-down"
                                                    class="w-4 h-4 text-kb-text-faint transition-transform duration-200"
                                                    x-bind:class="open ? 'rotate-180' : ''" />
                                            </button>

                                            <div x-show="open" x-collapse x-cloak class="mt-4">
                                                <flux:heading level="4" class="text-xs font-semibold text-kb-text-muted uppercase tracking-wider mb-4">Langkah-langkah Detail</flux:heading>

                                                <div class="relative">
                                                    {{-- Vertical line --}}
                                                    <div class="absolute top-0 bottom-0 left-[15px] w-px bg-kb-border-subtle"></div>

                                                    <div class="space-y-4">
                                                        @foreach ($plan->steps as $stepIndex => $step)
                                                            @php
                                                                $isToday = isset($step['date']) && $step['date'] === now()->format('Y-m-d');
                                                                $isPast = isset($step['date']) && $step['date'] < now()->format('Y-m-d');
                                                                $isFuture = isset($step['date']) && $step['date'] > now()->format('Y-m-d');
                                                            @endphp
                                                            <div class="relative flex items-start gap-4" wire:key="step-{{ $plan->id }}-{{ $stepIndex }}">
                                                                {{-- Step Node --}}
                                                                <div class="relative z-10 shrink-0">
                                                                    <div class="w-[30px] h-[30px] rounded-full flex items-center justify-center border-2 text-xs font-bold
                                                                        {{ $isToday ? 'bg-kb-blue-electric border-kb-blue-electric text-white shadow-lg shadow-kb-blue-electric/30' : '' }}
                                                                        {{ $isPast ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-400' : '' }}
                                                                        {{ $isFuture ? 'bg-kb-surface-2 border-kb-border text-kb-text-muted' : '' }}
                                                                    ">
                                                                        @if ($isPast)
                                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                                        @else
                                                                            {{ $stepIndex + 1 }}
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                {{-- Step Content --}}
                                                                <div class="flex-1 pb-1">
                                                                    <div class="flex items-center gap-2 mb-1">
                                                                        <span class="font-display font-semibold text-sm text-kb-text-primary">{{ $step['title'] }}</span>
                                                                        @if ($isToday)
                                                                            <flux:badge variant="soft" color="blue" size="xs">Hari Ini</flux:badge>
                                                                        @endif
                                                                    </div>
                                                                    <flux:text class="text-sm text-kb-text-secondary leading-relaxed">{{ $step['description'] }}</flux:text>
                                                                    @if (isset($step['date']))
                                                                        <div class="flex items-center gap-1.5 mt-1.5">
                                                                            <flux:icon name="calendar" class="w-3.5 h-3.5 text-kb-text-faint" />
                                                                            <span class="text-xs text-kb-text-faint">
                                                                                {{ \Carbon\Carbon::parse($step['date'])->format('d M Y') }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </flux:card>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <flux:card class="p-12 text-center rounded-2xl border-kb-border bg-kb-surface-1">
            <div class="w-16 h-16 rounded-2xl bg-kb-blue-electric/10 border border-kb-blue-electric/15 flex items-center justify-center mx-auto mb-4">
                <flux:icon name="list-bullet" class="w-8 h-8 text-kb-blue-electric/40" />
            </div>
            <flux:heading level="2" class="font-display text-xl font-semibold mb-2 text-kb-text-primary">Belum ada Action Plan</flux:heading>
            <flux:text class="text-kb-text-muted mb-6">
                Jalankan diagnosis terlebih dahulu untuk mendapatkan rencana aksi.
            </flux:text>
            <a href="{{ route('snapshot.create') }}">
                <flux:button variant="primary" class="btn-gradient-primary rounded-xl">Mulai Diagnosis</flux:button>
            </a>
        </flux:card>
    @endif
</div>
