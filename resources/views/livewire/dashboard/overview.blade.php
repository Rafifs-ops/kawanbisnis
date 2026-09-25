<div class="flex h-full w-full flex-1 flex-col gap-6">
    {{-- Header with Tujuan Bisnis on the top, beside the title --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="font-display text-2xl font-bold text-kb-text-primary">Dashboard</h1>
                @if ($passport)
                    <flux:text class="text-kb-text-muted">{{ $passport->business_name }} &middot;
                        {{ $passport->business_type }}</flux:text>
                @endif
            </div>

            @if ($activeGoal)
                <div
                    class="flex items-center gap-2 rounded-full border border-kb-blue-electric/20 bg-kb-blue-electric/5 px-3 py-1.5">
                    <span class="w-2 h-2 rounded-full bg-kb-blue-electric animate-pulse"></span>
                    <span class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">Tujuan Bisnis</span>
                    <span class="text-sm font-bold text-kb-text-primary">{{ $activeGoal }}</span>
                </div>
            @endif
        </div>

        @if (!$passport)
            <a href="{{ route('passport.index') }}">
                <flux:button variant="primary" class="btn-gradient-primary rounded-xl">Atur Profil Bisnis</flux:button>
            </a>
        @else
            <flux:button variant="primary" :href="route('snapshot.create')" class="btn-gradient-primary rounded-xl">
                Update Data Penjualan</flux:button>
        @endif
    </div>

    @if ($passport)
        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:card class="p-5 md:col-span-2 rounded-2xl border-kb-border bg-kb-surface-1">
                <flux:text class="text-sm text-kb-text-muted">Total Revenue</flux:text>
                <div class="kpi-number text-3xl text-kb-text-primary mt-1">
                    Rp{{ number_format($passport->snapshots()->sum('revenue'), 0, ',', '.') }}</div>
            </flux:card>

            <flux:card class="p-5 rounded-2xl border-kb-border bg-kb-surface-1">
                <flux:text class="text-sm text-kb-text-muted">Total Pesanan</flux:text>
                <div class="kpi-number text-2xl text-kb-text-primary mt-1">
                    {{ number_format($passport->snapshots()->sum('total_orders')) }}</div>
            </flux:card>
        </div>

        {{-- Timeline Plan Action — horizontal roadmap (left → right) --}}
        @if (count($timelineSteps) > 0)
            <flux:card class="p-5 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">
                        Timeline Plan Action</flux:heading>
                    <a href="{{ route('action-plan.index') }}"
                        class="text-sm font-medium text-kb-blue-electric hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="overflow-x-auto pb-2">
                    <div class="flex items-start min-w-max">
                        @foreach ($timelineSteps as $index => $step)
                            <div class="flex items-start">
                                {{-- Connector line --}}
                                @if ($index > 0)
                                    <div
                                        class="w-10 h-px bg-kb-border mt-4 shrink-0 {{ $step['status'] === 'pending' ? 'opacity-50' : '' }}">
                                    </div>
                                @endif

                                <div class="flex flex-col items-center w-36 shrink-0 text-center">
                                    <div @class([
                                        'w-8 h-8 rounded-full flex items-center justify-center border-2 text-xs font-bold shrink-0 z-10',
                                        'bg-kb-blue-electric border-kb-blue-electric text-white' =>
                                            $step['status'] === 'done',
                                        'bg-white border-kb-blue-electric text-kb-blue-electric timeline-node-running' =>
                                            $step['status'] === 'today',
                                        'bg-white border-kb-border text-kb-text-faint' =>
                                            $step['status'] === 'pending',
                                    ])>
                                        @if ($step['status'] === 'done')
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>

                                    <div class="mt-2 px-1 w-full">
                                        <div
                                            class="text-xs font-semibold text-kb-text-primary leading-tight line-clamp-2">
                                            {{ $step['title'] }}</div>
                                        <div class="text-[10px] text-kb-text-muted mt-0.5">
                                            {{ \Carbon\Carbon::parse($step['date'])->format('d M Y') }}
                                        </div>
                                        @if ($step['status'] === 'done')
                                            <flux:badge variant="soft" color="green" size="xs">Selesai
                                            </flux:badge>
                                        @elseif ($step['status'] === 'today')
                                            <flux:badge variant="soft" color="blue" size="xs">Hari Ini
                                            </flux:badge>
                                        @else
                                            <flux:badge variant="soft" size="xs">Berikutnya</flux:badge>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </flux:card>
        @endif

        {{-- Riwayat Diagnosis Terbaru — max 3 cards, column layout, newest first --}}
        @if ($recentDiagnoses->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Riwayat
                        Diagnosis</flux:heading>
                    <a href="{{ route('diagnosis.history') }}"
                        class="text-sm font-medium text-kb-blue-electric hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-3">
                    @foreach ($recentDiagnoses as $diagnosis)
                        <a href="{{ route('diagnosis.show', $diagnosis) }}" wire:navigate class="block">
                            <flux:card
                                class="p-4 rounded-2xl border-kb-border bg-kb-surface-1 card-glow cursor-pointer">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span
                                                class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">
                                                {{ $diagnosis->growthGoal->goal_type }}
                                            </span>
                                            @if ($diagnosis->status === 'completed')
                                                <flux:badge variant="soft" color="green" size="xs">Selesai
                                                </flux:badge>
                                            @elseif ($diagnosis->status === 'processing')
                                                <flux:badge variant="soft" color="yellow" size="xs">Sedang Diproses
                                                </flux:badge>
                                            @else
                                                <flux:badge variant="soft" color="red" size="xs">Gagal
                                                </flux:badge>
                                            @endif
                                        </div>
                                        <flux:text class="text-xs text-kb-text-muted">
                                            {{ $diagnosis->created_at->format('d M Y') }}
                                        </flux:text>
                                        @if ($diagnosis->status === 'completed' && ($diagnosis->business_diagnosis || $diagnosis->summary_diagnosis))
                                            <flux:text class="text-sm text-kb-text-secondary mt-1.5 block line-clamp-2">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($diagnosis->business_diagnosis ?? $diagnosis->summary_diagnosis), 140) }}
                                            </flux:text>
                                        @elseif ($diagnosis->status === 'processing')
                                            <flux:text class="text-sm text-kb-text-muted mt-1.5 block">
                                                Proses berjalan — buka untuk melihat detail.
                                            </flux:text>
                                        @endif
                                    </div>
                                    <flux:icon name="chevron-right" class="w-4 h-4 text-kb-text-faint shrink-0 mt-1" />
                                </div>
                            </flux:card>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        {{-- Empty State --}}
        <flux:card class="p-12 text-center rounded-2xl border-kb-border bg-kb-surface-1">
            <div
                class="w-16 h-16 rounded-2xl bg-kb-blue-electric flex items-center justify-center mx-auto mb-4 shadow-brand">
                <flux:icon name="rocket-launch" class="w-8 h-8 text-white" />
            </div>
            <flux:heading level="2" class="font-display text-xl font-bold mb-2 text-kb-text-primary">Selamat Datang
                di Kawan Bisnis!</flux:heading>
            <flux:text class="text-kb-text-muted mb-6 max-w-md mx-auto">
                Mulai dengan mengisi Profil Bisnis agar AI Growth Team bisa menganalisis bisnis Anda.
            </flux:text>
            <a href="{{ route('passport.index') }}">
                <flux:button variant="primary" class="btn-gradient-primary rounded-xl">Atur Profil Bisnis</flux:button>
            </a>
        </flux:card>
    @endif
</div>
