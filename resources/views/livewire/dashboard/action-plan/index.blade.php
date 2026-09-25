<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-kb-text-primary">Action Plan</h1>
            <p class="text-sm text-kb-text-muted mt-0.5">Riwayat rencana aksi berdasarkan diagnosis pertumbuhan.</p>
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
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-kb-blue-electric to-kb-blue-light text-white flex items-center justify-center text-sm font-bold shrink-0">
                            {{ $group['number'] }}
                        </div>
                        <div>
                            <h2 class="font-display text-lg font-semibold text-kb-text-primary">Diagnosis
                                ke-{{ $group['number'] }}</h2>
                            <p class="text-xs text-kb-text-muted">{{ $group['created_at'] }}</p>
                        </div>
                    </div>

                    {{-- Action Plans in this Diagnosis --}}
                    <div class="space-y-4 ml-0 md:ml-11">
                        @foreach ($group['plans'] as $plan)
                            <div class="rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm overflow-hidden">
                                {{-- Plan Header --}}
                                <div class="p-6 pb-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br from-kb-blue-electric to-kb-blue-light text-white flex items-center justify-center text-lg font-bold shrink-0">
                                                {{ $plan->priority_rank }}
                                            </div>
                                            <div>
                                                <h3 class="font-display text-lg font-semibold text-kb-text-primary">
                                                    {{ $plan->title }}</h3>
                                                <p class="text-sm text-kb-text-secondary mt-1">{{ $plan->description }}
                                                </p>
                                                <div class="flex flex-wrap gap-2 mt-3">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $plan->timeline_days }} hari
                                                    </span>
                                                    @if (isset($plan->target_kpi['metric']))
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                                            KPI: {{ $plan->target_kpi['metric'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{ route('check-in.create', $plan->id) }}" class="shrink-0">
                                            <button type="button"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-kb-border bg-white text-xs font-medium text-kb-text-primary shadow-sm hover:bg-gray-50 transition-colors">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Tandai Selesai
                                            </button>
                                        </a>
                                    </div>
                                </div>

                                {{-- Steps Timeline --}}
                                @if ($plan->steps && count($plan->steps) > 0)
                                    <div class="px-6 pb-6" x-data="{ open: false }">
                                        <div class="border-t border-kb-border-subtle pt-4">
                                            <button type="button"
                                                class="flex w-full items-center justify-between gap-2 text-left group"
                                                x-on:click="open = !open" x-bind:aria-expanded="open">
                                                <span
                                                    class="text-xs font-semibold text-kb-text-muted uppercase tracking-wider group-hover:text-kb-text-secondary">
                                                    Lihat Action Plan
                                                </span>
                                                <svg class="w-4 h-4 text-kb-text-faint transition-transform duration-200"
                                                    x-bind:class="open ? 'rotate-180' : ''" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>

                                            <div x-show="open" x-collapse x-cloak class="mt-4">
                                                <h4
                                                    class="text-xs font-semibold text-kb-text-muted uppercase tracking-wider mb-4">
                                                    Langkah-langkah Detail
                                                </h4>

                                                <div class="relative">
                                                    {{-- Vertical line --}}
                                                    <div
                                                        class="absolute top-0 bottom-0 left-[15px] w-px bg-kb-border-subtle">
                                                    </div>

                                                    <div class="space-y-4">
                                                        @foreach ($plan->steps as $stepIndex => $step)
                                                            @php
                                                                $isToday =
                                                                    isset($step['date']) &&
                                                                    $step['date'] === now()->format('Y-m-d');
                                                                $isPast =
                                                                    isset($step['date']) &&
                                                                    $step['date'] < now()->format('Y-m-d');
                                                                $isFuture =
                                                                    isset($step['date']) &&
                                                                    $step['date'] > now()->format('Y-m-d');
                                                            @endphp
                                                            <div class="relative flex items-start gap-4"
                                                                wire:key="step-{{ $plan->id }}-{{ $stepIndex }}">
                                                                {{-- Step Node --}}
                                                                <div class="relative z-10 shrink-0">
                                                                    <div
                                                                        class="w-[30px] h-[30px] rounded-full flex items-center justify-center border-2 text-xs font-bold
                                                                        {{ $isToday ? 'bg-kb-blue-electric border-kb-blue-electric text-white shadow-lg shadow-kb-blue-electric/30' : '' }}
                                                                        {{ $isPast ? 'bg-emerald-500/15 border-emerald-500/30 text-emerald-400' : '' }}
                                                                        {{ $isFuture ? 'bg-kb-surface-2 border-kb-border text-kb-text-muted' : '' }}
                                                                    ">
                                                                        @if ($isPast)
                                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                                viewBox="0 0 24 24"
                                                                                stroke="currentColor"
                                                                                stroke-width="2.5">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M5 13l4 4L19 7" />
                                                                            </svg>
                                                                        @else
                                                                            {{ $stepIndex + 1 }}
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                {{-- Step Content --}}
                                                                <div class="flex-1 pb-1">
                                                                    <div class="flex items-center gap-2 mb-1">
                                                                        <span
                                                                            class="font-display font-semibold text-sm text-kb-text-primary">{{ $step['title'] }}</span>
                                                                        @if ($isToday)
                                                                            <span
                                                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800">
                                                                                Hari Ini
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                    <p
                                                                        class="text-sm text-kb-text-secondary leading-relaxed">
                                                                        {{ $step['description'] }}</p>
                                                                    @if (isset($step['date']))
                                                                        <div class="flex items-center gap-1.5 mt-1.5">
                                                                            <svg class="w-3.5 h-3.5 text-kb-text-faint"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                            </svg>
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
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-12 text-center rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
            <div
                class="w-16 h-16 rounded-2xl bg-kb-blue-electric/10 border border-kb-blue-electric/15 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-kb-blue-electric/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h2 class="font-display text-xl font-semibold mb-2 text-kb-text-primary">Belum ada Action Plan</h2>
            <p class="text-sm text-kb-text-muted mb-6">
                Jalankan diagnosis terlebih dahulu untuk mendapatkan rencana aksi.
            </p>
            <a href="{{ route('snapshot.create') }}">
                <button type="button"
                    class="btn-gradient-primary rounded-xl px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90 transition-opacity">
                    Mulai Diagnosis
                </button>
            </a>
        </div>
    @endif
</div>
