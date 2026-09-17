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
            <a href="{{ route('dashboard') }}" class="text-white text-sm">
                Kembali
            </a>
        </div>
    </div>

    @if ($isProcessing)
        {{-- ═══ Live Agent Workspace — Roadmap Timeline ═══ --}}
        <div wire:poll.3s="refreshStatus">
            {{-- Header Block --}}
            <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1 mb-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-kb-blue-electric to-kb-blue-light flex items-center justify-center shrink-0 shadow-brand">
                            <flux:icon name="sparkles" class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="live-dot w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                                <span class="text-xs font-semibold text-emerald-400 uppercase tracking-wider">Live Agent Workspace</span>
                            </div>
                            <flux:heading level="2" class="font-display text-xl font-bold text-kb-text-primary mb-1">Tim AI Growth Team</flux:heading>
                            <flux:text class="text-kb-text-muted text-sm">
                                @if ($completedCount === 0)
                                    Memulai analisis bisnis Anda...
                                @elseif ($completedCount < 4)
                                    {{ $completedCount }} dari 4 agen sedang bekerja menganalisis bisnis Anda...
                                @endif
                            </flux:text>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mt-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">Progres Analisis</span>
                        <span class="text-xs font-bold text-kb-blue-light">{{ $completedCount }}/4 Agen Selesai</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-kb-surface-2 overflow-hidden">
                        <div
                            class="progress-bar-fill h-full rounded-full transition-colors duration-500 {{ $completedCount === 4 ? 'bg-emerald-400' : 'bg-gradient-to-r from-kb-blue-electric to-kb-blue-light' }}"
                            style="width: {{ ($completedCount / 4) * 100 }}%"
                        ></div>
                    </div>
                </div>
            </flux:card>

            {{-- Roadmap Timeline --}}
            <div class="relative">
                {{-- Vertical line — desktop center, mobile left --}}
                <div class="absolute top-0 bottom-0 left-5 md:left-1/2 w-px bg-kb-border md:-translate-x-px"></div>

                <div class="space-y-8 md:space-y-12">
                    @foreach ($agents as $index => $agent)
                        @php
                            $isLeft = $index % 2 === 0;
                            $isCompleted = $agent['status'] === 'completed';
                            $isRunning = $agent['status'] === 'running';
                            $isWaiting = $agent['status'] === 'waiting';

                            $colorMap = [
                                'blue' => [
                                    'bg' => 'bg-blue-500/15',
                                    'border' => 'border-blue-500/25',
                                    'icon' => 'text-blue-400',
                                    'node' => 'bg-blue-500',
                                    'badgeCompleted' => 'bg-blue-500/15 text-blue-400 border-blue-500/25',
                                ],
                                'emerald' => [
                                    'bg' => 'bg-emerald-500/15',
                                    'border' => 'border-emerald-500/25',
                                    'icon' => 'text-emerald-400',
                                    'node' => 'bg-emerald-500',
                                    'badgeCompleted' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/25',
                                ],
                                'amber' => [
                                    'bg' => 'bg-amber-500/15',
                                    'border' => 'border-amber-500/25',
                                    'icon' => 'text-amber-400',
                                    'node' => 'bg-amber-500',
                                    'badgeCompleted' => 'bg-amber-500/15 text-amber-400 border-amber-500/25',
                                ],
                                'rose' => [
                                    'bg' => 'bg-rose-500/15',
                                    'border' => 'border-rose-500/25',
                                    'icon' => 'text-rose-400',
                                    'node' => 'bg-rose-500',
                                    'badgeCompleted' => 'bg-rose-500/15 text-rose-400 border-rose-500/25',
                                ],
                            ];
                            $colors = $colorMap[$agent['color']];
                        @endphp

                        {{-- Timeline Row --}}
                        <div class="relative flex items-start {{ $isLeft ? 'md:flex-row' : 'md:flex-row-reverse' }} flex-row">
                            {{-- Card --}}
                            <div class="w-full md:w-[calc(50%-2rem)] ml-12 md:ml-0 {{ $isLeft ? 'md:pr-8' : 'md:pl-8' }}">
                                <div class="p-5 rounded-2xl border {{ $isWaiting ? 'border-kb-border-subtle bg-kb-surface-0 opacity-50' : 'border-kb-border bg-kb-surface-1' }} transition-all duration-300">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl {{ $colors['bg'] }} border {{ $colors['border'] }} flex items-center justify-center">
                                                <flux:icon name="{{ $agent['icon'] }}" class="w-5 h-5 {{ $colors['icon'] }}" />
                                            </div>
                                            <div>
                                                <div class="font-display font-semibold text-sm text-kb-text-primary">{{ $agent['name'] }}</div>
                                                <div class="text-xs text-kb-text-muted">{{ $agent['role'] }}</div>
                                            </div>
                                        </div>
                                        {{-- Status Badge --}}
                                        @if ($isCompleted)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $colors['badgeCompleted'] }} border">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                Selesai
                                            </span>
                                        @elseif ($isRunning)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-400 border border-amber-500/25">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 live-dot"></span>
                                                Memproses...
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-kb-surface-2 text-kb-text-faint border border-kb-border-subtle">
                                                Menunggu
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Agent description --}}
                                    @if ($isCompleted)
                                        <div class="mt-3 pt-3 border-t border-kb-border-subtle">
                                            <div class="flex items-center gap-1.5 text-xs {{ $colors['icon'] }}">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                <span class="font-semibold">Analisis selesai</span>
                                            </div>
                                        </div>
                                    @elseif ($isRunning)
                                        <div class="mt-3 pt-3 border-t border-kb-border-subtle">
                                            <div class="flex items-center gap-2">
                                                <div class="flex gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse" style="animation-delay: 0.2s"></span>
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse" style="animation-delay: 0.4s"></span>
                                                </div>
                                                <span class="text-xs text-amber-400/80">Sedang menganalisis...</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Timeline Node --}}
                            <div class="absolute left-0 md:left-1/2 -translate-x-1/2 z-10">
                                <div class="
                                    w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all duration-500
                                    {{ $isCompleted ? $colors['node'] . ' border-' . $agent['color'] . '-400 text-white shadow-lg' : '' }}
                                    {{ $isRunning ? 'bg-kb-surface-solid border-amber-400 text-amber-400 timeline-node-running' : '' }}
                                    {{ $isWaiting ? 'bg-kb-surface-solid border-kb-border-subtle text-kb-text-faint' : '' }}
                                ">
                                    @if ($isCompleted)
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    @elseif ($isRunning)
                                        <flux:icon name="arrow-path" class="w-4 h-4 animate-spin" />
                                    @else
                                        <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Spacer for opposite side (desktop only) --}}
                            <div class="hidden md:block w-[calc(50%-2rem)]"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        {{-- ═══ Analysis Complete Banner ═══ --}}
        @if ($completedCount === 4)
            <div id="diagnosis-result">
                <flux:card class="p-6 rounded-2xl border-emerald-500/20 bg-emerald-500/5 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <flux:heading level="2" class="font-display text-lg font-bold text-kb-text-primary">Analisis Selesai</flux:heading>
                                <flux:text class="text-sm text-kb-text-muted">4/4 agen telah menyelesaikan diagnosa pertumbuhan</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:card>
            </div>
        @endif

        {{-- ═══ Diagnosis Results (existing) ═══ --}}
        {{-- 1. Business Diagnosis --}}
        <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-kb-blue-electric to-kb-blue-light text-white flex items-center justify-center text-sm font-bold">1</div>
                <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Business Diagnosis</flux:heading>
            </div>
            <flux:text class="text-kb-text-secondary leading-relaxed">{{ $diagnosis->business_diagnosis ?? $diagnosis->summary_diagnosis }}</flux:text>
        </flux:card>

        {{-- 2. Root Cause / Hypothesis --}}
        @if ($diagnosis->root_causes)
            <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-kb-blue-electric/15 border border-kb-blue-electric/20 text-kb-blue-light flex items-center justify-center text-sm font-bold">2</div>
                    <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Root Cause / Hipotesis</flux:heading>
                </div>
                <ul class="list-disc list-inside space-y-1.5 text-sm text-kb-text-secondary">
                    @foreach ($diagnosis->root_causes as $cause)
                        <li>{{ $cause }}</li>
                    @endforeach
                </ul>
            </flux:card>
        @endif

        {{-- 3. Growth Opportunity --}}
        @if ($diagnosis->opportunities)
            <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm font-bold">3</div>
                    <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Peluang Pertumbuhan</flux:heading>
                </div>
                <ul class="list-disc list-inside space-y-1.5 text-sm text-kb-text-secondary">
                    @foreach ($diagnosis->opportunities as $opp)
                        <li>{{ $opp }}</li>
                    @endforeach
                </ul>
            </flux:card>
        @endif

        {{-- 4. Top 3 Recommendations with Steps --}}
        @if (count($diagnosis->actionPlans) > 0)
            <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-kb-blue-electric to-kb-blue-light text-white flex items-center justify-center text-sm font-bold">4</div>
                    <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Top 3 Rekomendasi</flux:heading>
                </div>
                <div class="space-y-4">
                    @foreach ($diagnosis->actionPlans->sortBy('priority_rank') as $plan)
                        <div class="p-4 rounded-xl bg-kb-surface-2">
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full bg-kb-blue-light/15 text-kb-blue-light flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                    {{ $plan->priority_rank }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-display font-semibold text-sm text-kb-text-primary">{{ $plan->title }}</span>
                                    </div>
                                    <flux:text class="text-sm text-kb-text-secondary mt-1">{{ $plan->description }}</flux:text>

                                    {{-- Steps --}}
                                    @if ($plan->steps && count($plan->steps) > 0)
                                        <div class="mt-3 pt-3 border-t border-kb-border-subtle">
                                            <div class="relative">
                                                <div class="absolute top-0 bottom-0 left-[11px] w-px bg-kb-border-subtle"></div>
                                                <div class="space-y-2.5">
                                                    @foreach ($plan->steps as $step)
                                                        @php
                                                            $isToday = isset($step['date']) && $step['date'] === now()->format('Y-m-d');
                                                        @endphp
                                                        <div class="relative flex items-start gap-3">
                                                            <div class="relative z-10 shrink-0 w-[22px] h-[22px] rounded-full flex items-center justify-center border text-[9px] font-bold
                                                                {{ $isToday ? 'bg-kb-blue-electric border-kb-blue-electric text-white' : 'bg-kb-surface-1 border-kb-border text-kb-text-faint' }}
                                                            ">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-xs font-semibold text-kb-text-primary">{{ $step['title'] }}</span>
                                                                    @if ($isToday)
                                                                        <flux:badge variant="soft" color="blue" size="xs">Hari Ini</flux:badge>
                                                                    @endif
                                                                </div>
                                                                <p class="text-xs text-kb-text-muted mt-0.5 leading-relaxed">{{ $step['description'] }}</p>
                                                                @if (isset($step['date']))
                                                                    <span class="text-[10px] text-kb-text-faint mt-0.5 inline-block">
                                                                        {{ \Carbon\Carbon::parse($step['date'])->format('d M Y') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        @endif

        {{-- 5. Action Plan --}}
        @if (count($diagnosis->actionPlans) > 0)
            <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-kb-blue-light/15 text-kb-blue-light flex items-center justify-center text-sm font-bold">5</div>
                    <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Action Plan</flux:heading>
                </div>

                @php
                    $shortTerm = $diagnosis->actionPlans->sortBy('priority_rank')->filter(fn ($p) => $p->timeline_days <= 7);
                    $longTerm = $diagnosis->actionPlans->sortBy('priority_rank')->filter(fn ($p) => $p->timeline_days > 7);
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-kb-surface-2">
                        <flux:heading level="3" class="text-sm font-semibold mb-2 text-kb-text-muted uppercase tracking-wide">7 Hari ke Depan</flux:heading>
                        @if ($shortTerm->isNotEmpty())
                            <ul class="list-disc list-inside space-y-1 text-sm text-kb-text-secondary">
                                @foreach ($shortTerm as $plan)
                                    <li>{{ $plan->title }}</li>
                                @endforeach
                            </ul>
                        @else
                            <flux:text class="text-sm text-kb-text-faint italic">Tidak ada aksi jangka pendek.</flux:text>
                        @endif
                    </div>
                    <div class="p-4 rounded-xl bg-kb-surface-2">
                        <flux:heading level="3" class="text-sm font-semibold mb-2 text-kb-text-muted uppercase tracking-wide">30 Hari ke Depan</flux:heading>
                        @if ($longTerm->isNotEmpty())
                            <ul class="list-disc list-inside space-y-1 text-sm text-kb-text-secondary">
                                @foreach ($longTerm as $plan)
                                    <li>{{ $plan->title }}</li>
                                @endforeach
                            </ul>
                        @else
                            <flux:text class="text-sm text-kb-text-faint italic">Tidak ada aksi jangka panjang.</flux:text>
                        @endif
                    </div>
                </div>
            </flux:card>
        @endif

        {{-- 6. KPI --}}
        @if ($diagnosis->kpi_metrics)
            <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-kb-blue-electric to-kb-blue-light text-white flex items-center justify-center text-sm font-bold">6</div>
                    <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">KPI yang Harus Dipantau</flux:heading>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach ($diagnosis->kpi_metrics as $kpi)
                        <div class="rounded-xl border border-kb-border bg-kb-surface-2 p-4">
                            <flux:text class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">{{ $kpi['metric'] }}</flux:text>
                            <div class="mt-2">
                                <span class="kpi-number text-xl text-kb-blue-light">{{ $kpi['target'] }}</span>
                                <span class="text-sm text-kb-text-muted ml-1">{{ $kpi['unit'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:card>
        @endif
    @endif
</div>
