<div class="flex h-full w-full flex-1 flex-col gap-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-kb-text-primary">AI Growth Diagnosis</h1>
            <flux:text class="text-kb-text-muted">
                Goal: {{ $diagnosis->growthGoal->goal_type }} &middot;
                {{ $diagnosis->created_at->diffForHumans() }}
            </flux:text>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('diagnosis.history') }}" wire:navigate class="text-sm font-medium text-kb-blue-electric hover:underline">
                Riwayat Diagnosis
            </a>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-kb-blue-electric hover:underline">
                Kembali
            </a>
        </div>
    </div>

    @if ($isFailed)
        {{-- ═══ Diagnosis Failed — notify + retry ═══ --}}
        <div wire:poll.5s="refreshStatus">
            <flux:card class="p-6 rounded-2xl border-red-300 bg-red-50 mb-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-red-100 border border-red-200 flex items-center justify-center shrink-0">
                            <flux:icon name="exclamation-triangle" class="w-6 h-6 text-red-600" />
                        </div>
                        <div>
                            <flux:heading level="2" class="font-display text-lg font-bold text-kb-text-primary">Diagnosis Gagal</flux:heading>
                            <flux:text class="text-sm text-kb-text-muted">
                                Proses agen AI tidak dapat menyelesaikan diagnosis. Silakan coba lagi.
                            </flux:text>
                            @if ($diagnosis->summary_diagnosis && $diagnosis->summary_diagnosis !== 'Sedang diproses...')
                                <flux:text class="text-xs text-red-700 mt-1 block">{{ $diagnosis->summary_diagnosis }}</flux:text>
                            @endif
                        </div>
                    </div>
                    <flux:button
                        wire:click="retry"
                        wire:loading.attr="disabled"
                        variant="primary"
                        class="btn-gradient-primary rounded-xl shrink-0"
                    >
                        <span wire:loading.remove wire:target="retry">Coba Lagi</span>
                        <span wire:loading wire:target="retry">Memproses...</span>
                    </flux:button>
                </div>
            </flux:card>

            <flux:card class="p-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide mb-3">Status Analisis ({{ $completedCount }}/4 selesai)</div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($agents as $agent)
                        <span @class([
                            'inline-flex items-center gap-1 px-2 py-1 rounded-full text-[11px] font-semibold border',
                            'bg-emerald-50 text-emerald-700 border-emerald-200' => $agent['status'] === 'completed',
                            'bg-red-50 text-red-700 border-red-200' => $agent['status'] === 'failed',
                            'bg-zinc-100 text-zinc-500 border-zinc-200' => $agent['status'] === 'waiting',
                        ])>
                            {{ $agent['name'] }} ·
                            @if ($agent['status'] === 'completed') Selesai
                            @elseif ($agent['status'] === 'failed') Gagal
                            @else Menunggu
                            @endif
                        </span>
                    @endforeach
                </div>
            </flux:card>
        </div>
    @elseif ($isProcessing)
        {{-- ═══ Live Process — timeline on the left, cards in one column on the right ═══ --}}
        <div wire:poll.3s="refreshStatus">
            {{-- Progress summary --}}
            <flux:card class="p-5 rounded-2xl border-kb-border bg-kb-surface-1 mb-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-kb-blue-electric flex items-center justify-center shrink-0 shadow-brand">
                        <flux:icon name="sparkles" class="w-6 h-6 text-white" />
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="live-dot w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                            <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Sedang Berjalan</span>
                        </div>
                        <flux:heading level="2" class="font-display text-xl font-bold text-kb-text-primary mb-1">AI Growth Team Bekerja</flux:heading>
                        <flux:text class="text-kb-text-muted text-sm">
                            @if ($completedCount === 0)
                                Memulai analisis bisnis Anda...
                            @elseif ($completedCount < 4)
                                {{ $completedCount }} dari 4 analisis selesai — lanjut menganalisis...
                            @else
                                Semua analisis selesai — merangkum hasil diagnosis...
                            @endif
                        </flux:text>

                        <div class="mt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">Progres Analisis</span>
                                <span class="text-xs font-bold text-kb-blue-electric">{{ $completedCount }}/4 Selesai</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-zinc-200 overflow-hidden">
                                <div
                                    class="progress-bar-fill h-full rounded-full transition-colors duration-500 {{ $completedCount === 4 ? 'bg-emerald-500' : 'bg-kb-blue-electric' }}"
                                    style="width: {{ min(100, ($completedCount / 4) * 100) }}%"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </flux:card>

            {{-- Timeline: line + indicators on the LEFT, cards stacked in ONE column on the RIGHT --}}
            <div class="relative">
                <div class="absolute top-0 bottom-0 left-5 w-px bg-kb-border"></div>

                <div class="space-y-4">
                    @foreach ($agents as $index => $agent)
                        @php
                            $isCompleted = $agent['status'] === 'completed';
                            $isRunning = $agent['status'] === 'running';
                            $isFailed = $agent['status'] === 'failed';
                            $isWaiting = $agent['status'] === 'waiting';

                            $colorMap = [
                                'blue' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'icon' => 'text-blue-700', 'node' => 'bg-blue-600', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                'emerald' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'icon' => 'text-emerald-700', 'node' => 'bg-emerald-600', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                'amber' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'icon' => 'text-amber-700', 'node' => 'bg-amber-500', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                'rose' => ['bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'icon' => 'text-rose-700', 'node' => 'bg-rose-500', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200'],
                            ];
                            $colors = $colorMap[$agent['color']];
                        @endphp

                        <div class="relative flex items-start gap-4 pl-0">
                            {{-- Timeline node (left) --}}
                            <div class="relative z-10 shrink-0 w-10">
                                <div @class([
                                    'w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-500',
                                    $colors['node'] . ' border-white text-white shadow-md' => $isCompleted,
                                    'bg-white border-amber-500 text-amber-600 timeline-node-running' => $isRunning,
                                    'bg-red-50 border-red-400 text-red-600' => $isFailed,
                                    'bg-white border-zinc-300 text-zinc-400' => $isWaiting,
                                ])>
                                    @if ($isCompleted)
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    @elseif ($isRunning)
                                        <flux:icon name="arrow-path" class="w-4 h-4 animate-spin" />
                                    @elseif ($isFailed)
                                        <flux:icon name="exclamation-triangle" class="w-4 h-4" />
                                    @else
                                        <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Card (right, full available width) --}}
                            <div class="flex-1 min-w-0 @if ($isWaiting) opacity-60 @endif">
                                <div class="p-4 rounded-2xl border border-kb-border bg-kb-surface-1 transition-all duration-300">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-xl {{ $colors['bg'] }} border {{ $colors['border'] }} flex items-center justify-center shrink-0">
                                                <flux:icon name="{{ $agent['icon'] }}" class="w-5 h-5 {{ $colors['icon'] }}" />
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-display font-semibold text-sm text-kb-text-primary">{{ $agent['name'] }}</div>
                                                <div class="text-xs text-kb-text-muted">{{ $agent['role'] }}</div>
                                            </div>
                                        </div>

                                        {{-- Status: menunggu / memproses / selesai / gagal --}}
                                        @if ($isCompleted)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $colors['badge'] }} shrink-0">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                Selesai
                                            </span>
                                        @elseif ($isRunning)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200 shrink-0">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 live-dot"></span>
                                                Memproses
                                            </span>
                                        @elseif ($isFailed)
                                            <span class="inline-flex items-center gap-2 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200 shrink-0">
                                                Gagal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-zinc-100 text-zinc-500 border border-zinc-200 shrink-0">
                                                Menunggu
                                            </span>
                                        @endif
                                    </div>

                                    @if ($isFailed)
                                        <div class="mt-3 pt-3 border-t border-kb-border">
                                            <flux:button size="xs" variant="subtle" wire:click="retry" wire:loading.attr="disabled" class="text-red-600">
                                                Coba Lagi
                                            </flux:button>
                                        </div>
                                    @elseif ($isCompleted)
                                        <div class="mt-3 pt-3 border-t border-kb-border">
                                            <div class="flex items-center gap-1.5 text-xs {{ $colors['icon'] }}">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="font-semibold">Analisis selesai</span>
                                            </div>
                                        </div>
                                    @elseif ($isRunning)
                                        <div class="mt-3 pt-3 border-t border-kb-border">
                                            <div class="flex items-center gap-2">
                                                <div class="flex gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse" style="animation-delay: 0.2s"></span>
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse" style="animation-delay: 0.4s"></span>
                                                </div>
                                                <span class="text-xs text-amber-700">Sedang menganalisis...</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        {{-- ═══ Analysis Complete — ordered, actionable result ═══ --}}
        @if ($diagnosis->status === 'completed')
            <div id="diagnosis-result">
                <flux:card class="p-5 rounded-2xl border-emerald-200 bg-emerald-50 mb-2">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <flux:heading level="2" class="font-display text-lg font-bold text-kb-text-primary">Analisis Selesai</flux:heading>
                            <flux:text class="text-sm text-kb-text-muted">Ringkasan, temuan, dan rekomendasi siap ditindaklanjuti.</flux:text>
                        </div>
                    </div>
                </flux:card>
            </div>
        @endif

        {{-- 1. Ringkasan: masalah utama & prioritas tindakan --}}
        <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-kb-blue-electric text-white flex items-center justify-center text-sm font-bold">1</div>
                <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Ringkasan</flux:heading>
            </div>
            <flux:text class="text-kb-text-secondary leading-relaxed">{{ $diagnosis->business_diagnosis ?? $diagnosis->summary_diagnosis }}</flux:text>

            @if (count($diagnosis->actionPlans) > 0)
                <div class="mt-4 pt-4 border-t border-kb-border">
                    <flux:text class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">Prioritas Tindakan</flux:text>
                    <flux:text class="text-sm text-kb-text-primary font-medium mt-1 block">
                        {{ $diagnosis->actionPlans->sortBy('priority_rank')->first()->title }}
                    </flux:text>
                </div>
            @endif
        </flux:card>

        {{-- 2. Dasar analisis: periode data, target, angka --}}
        @php
            $passport = $diagnosis->growthGoal->businessPassport;
            $snapshot = $passport->snapshots()->latest('created_at')->first();
        @endphp
        <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-full bg-kb-blue-electric/10 border border-kb-blue-electric/20 text-kb-blue-electric flex items-center justify-center text-sm font-bold">2</div>
                <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Dasar Analisis</flux:heading>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="rounded-xl border border-kb-border bg-kb-surface-2 p-4">
                    <flux:text class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">Periode Data</flux:text>
                    <div class="text-sm font-semibold text-kb-text-primary mt-1">
                        @if ($snapshot)
                            {{ $snapshot->period_start->format('d M Y') }} – {{ $snapshot->period_end->format('d M Y') }}
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div class="rounded-xl border border-kb-border bg-kb-surface-2 p-4">
                    <flux:text class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">Target Bisnis</flux:text>
                    <div class="text-sm font-semibold text-kb-text-primary mt-1">{{ $diagnosis->growthGoal->goal_type }}</div>
                    @if (! empty($diagnosis->growthGoal->target_metrics['target']))
                        @php
                            $goalUnit = $diagnosis->growthGoal->target_metrics['unit'] ?? 'nominal';
                            $goalTarget = $diagnosis->growthGoal->target_metrics['target'];
                        @endphp
                        <div class="text-xs text-kb-text-muted mt-0.5">
                            Target:
                            @if ($goalUnit === 'percent')
                                {{ rtrim(rtrim(number_format((float) $goalTarget, 2, ',', '.'), '0'), ',') }}%
                            @else
                                Rp{{ number_format((float) $goalTarget, 0, ',', '.') }}
                            @endif
                        </div>
                    @endif
                </div>
                <div class="rounded-xl border border-kb-border bg-kb-surface-2 p-4">
                    <flux:text class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">Revenue</flux:text>
                    <div class="text-sm font-semibold text-kb-text-primary mt-1">
                        {{ $snapshot ? 'Rp'.number_format($snapshot->revenue, 0, ',', '.') : '—' }}
                    </div>
                </div>
                <div class="rounded-xl border border-kb-border bg-kb-surface-2 p-4">
                    <flux:text class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">Total Pesanan</flux:text>
                    <div class="text-sm font-semibold text-kb-text-primary mt-1">
                        {{ $snapshot ? number_format($snapshot->total_orders) : '—' }}
                    </div>
                </div>
            </div>
        </flux:card>

        {{-- 3. Temuan (fakta) & dugaan penyebab (hipotesis) --}}
        <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-full bg-kb-blue-electric/10 border border-kb-blue-electric/20 text-kb-blue-electric flex items-center justify-center text-sm font-bold">3</div>
                <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Temuan &amp; Dugaan Penyebab</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-kb-border bg-kb-surface-2 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <flux:icon name="check-badge" class="w-4 h-4 text-emerald-600" />
                        <flux:text class="text-xs font-semibold text-emerald-700 uppercase tracking-wide">Temuan (Fakta)</flux:text>
                    </div>
                    @if ($diagnosis->key_findings)
                        <ul class="list-disc list-inside space-y-1.5 text-sm text-kb-text-secondary">
                            @foreach ($diagnosis->key_findings as $finding)
                                <li>{{ $finding }}</li>
                            @endforeach
                        </ul>
                    @elseif ($diagnosis->opportunities)
                        <ul class="list-disc list-inside space-y-1.5 text-sm text-kb-text-secondary">
                            @foreach ($diagnosis->opportunities as $opp)
                                <li>{{ $opp }}</li>
                            @endforeach
                        </ul>
                    @else
                        <flux:text class="text-sm text-kb-text-faint italic">Tidak ada temuan terpisah.</flux:text>
                    @endif
                </div>

                <div class="rounded-xl border border-kb-border bg-kb-surface-2 p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <flux:icon name="question-mark-circle" class="w-4 h-4 text-amber-600" />
                        <flux:text class="text-xs font-semibold text-amber-700 uppercase tracking-wide">Dugaan Penyebab (Hipotesis)</flux:text>
                    </div>
                    @if ($diagnosis->root_causes)
                        <ul class="list-disc list-inside space-y-1.5 text-sm text-kb-text-secondary">
                            @foreach ($diagnosis->root_causes as $cause)
                                <li>{{ $cause }}</li>
                            @endforeach
                        </ul>
                    @else
                        <flux:text class="text-sm text-kb-text-faint italic">Belum ada dugaan penyebab.</flux:text>
                    @endif
                </div>
            </div>
        </flux:card>

        {{-- 4. Tiga rekomendasi utama: judul, alasan, KPI --}}
        @if (count($diagnosis->actionPlans) > 0)
            <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-kb-blue-electric text-white flex items-center justify-center text-sm font-bold">4</div>
                    <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Tiga Rekomendasi Utama</flux:heading>
                </div>
                <div class="space-y-4">
                    @foreach ($diagnosis->actionPlans->sortBy('priority_rank')->take(3) as $plan)
                        <div class="p-4 rounded-xl border border-kb-border bg-kb-surface-2">
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full bg-kb-blue-electric text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                    {{ $plan->priority_rank }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-display font-semibold text-sm text-kb-text-primary">{{ $plan->title }}</div>
                                    <flux:text class="text-sm text-kb-text-secondary mt-1">{{ $plan->description }}</flux:text>
                                    @if (isset($plan->target_kpi['metric']))
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <flux:badge variant="soft" color="blue" size="xs">KPI: {{ $plan->target_kpi['metric'] }}</flux:badge>
                                            @if (isset($plan->target_kpi['target']))
                                                <flux:badge variant="soft" size="xs">Target: {{ $plan->target_kpi['target'] }}</flux:badge>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        @endif

        {{-- 5. Lihat Action Plan --}}
        @if (count($diagnosis->actionPlans) > 0)
            <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Langkah Implementasi Lengkap</flux:heading>
                        <flux:text class="text-sm text-kb-text-muted">Detail langkah harian tersedia di halaman Action Plan.</flux:text>
                    </div>
                    <a href="{{ route('action-plan.index') }}" wire:navigate>
                        <flux:button variant="primary" class="btn-gradient-primary rounded-xl">
                            Lihat Action Plan
                            <flux:icon name="arrow-right" class="w-4 h-4 ml-1" />
                        </flux:button>
                    </a>
                </div>
            </flux:card>
        @endif
    @endif
</div>
