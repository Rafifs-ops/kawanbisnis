<div class="flex h-full w-full flex-1 flex-col gap-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="font-display text-2xl font-bold text-kb-text-primary">Dashboard</h1>
            @if ($passport)
                <flux:text class="text-kb-text-muted">{{ $passport->business_name }} &middot; {{ $passport->business_type }}</flux:text>
            @endif
            </div>
        </div>
        @if (! $passport)
            <a href="{{ route('passport.index') }}">
                <flux:button variant="primary" class="btn-gradient-primary rounded-xl">Atur Profil Bisnis</flux:button>
            </a>
        @endif
    </div>

    @if ($passport)
        {{-- Stats Grid — Varied Sizes --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Primary stat: Revenue (larger) --}}
            <flux:card class="p-5 md:col-span-2 rounded-2xl border-kb-border bg-kb-surface-1">
                <flux:text class="text-sm text-kb-text-muted">Total Revenue</flux:text>
                <div class="kpi-number text-3xl text-kb-text-primary mt-1">Rp{{ number_format($passport->snapshots()->sum('revenue'), 0, ',', '.') }}</div>
            </flux:card>

            {{-- Secondary stats --}}
            <flux:card class="p-5 rounded-2xl border-kb-border bg-kb-surface-1">
                <flux:text class="text-sm text-kb-text-muted">Total Pesanan</flux:text>
                <div class="kpi-number text-2xl text-kb-text-primary mt-1">{{ number_format($passport->snapshots()->sum('total_orders')) }}</div>
            </flux:card>

            <flux:card class="p-5 rounded-2xl border-kb-border bg-kb-surface-1">
                <flux:text class="text-sm text-kb-text-muted">Diagnosis Selesai</flux:text>
                <div class="kpi-number text-2xl text-kb-text-primary mt-1">
                    {{ $passport->growthGoals()->withCount('growthDiagnoses')->get()->sum('growth_diagnoses_count') }}
                </div>
            </flux:card>
        </div>

        {{-- Active Goal --}}
        @if ($passport->growthGoals()->where('status', 'active')->first())
            <flux:card class="p-4 rounded-2xl border-kb-blue-electric/20 bg-kb-blue-electric/5">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-kb-blue-light animate-pulse"></div>
                    <div>
                        <flux:text class="text-xs text-kb-text-muted uppercase tracking-wide">Tujuan Bisnis</flux:text>
                        <div class="kpi-number text-lg text-kb-text-primary">
                            {{ $passport->growthGoals()->where('status', 'active')->first()?->goal_type }}
                        </div>
                    </div>
                </div>
            </flux:card>
        @endif

        {{-- Latest Diagnosis Summary --}}
        @if ($latestDiagnosis && $latestDiagnosis->summary_diagnosis !== 'Sedang diproses...')
            <a href="{{ route('diagnosis.show', $latestDiagnosis) }}">
                <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1 card-glow cursor-pointer">
                    <div class="flex items-center gap-2 mb-2">
                        <flux:icon name="sparkles" class="w-5 h-5 text-kb-blue-light" />
                        <flux:heading level="3" class="font-display font-semibold text-kb-text-primary">Diagnosis Terakhir</flux:heading>
                    </div>
                    <flux:text class="text-sm text-kb-text-secondary line-clamp-2">{{ $latestDiagnosis->business_diagnosis ?? $latestDiagnosis->summary_diagnosis }}</flux:text>
                </flux:card>
            </a>
        @endif

        {{-- Action Plans --}}
        @if ($latestDiagnosis && count($latestDiagnosis->actionPlans) > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h1 class="font-display text-lg font-semibold text-kb-text-primary">Rencana Aksi</h1>
                    <a href="{{ route('action-plan.index') }}" class="text-white text-sm">
                        Lihat Semua
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach ($latestDiagnosis->actionPlans->sortBy('priority_rank') as $plan)
                        <flux:card class="p-4 rounded-2xl border-kb-border bg-kb-surface-1">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-kb-blue-electric to-kb-blue-light text-white flex items-center justify-center text-sm font-bold">
                                        {{ $plan->priority_rank }}
                                    </div>
                                    <div>
                                        <flux:heading level="3" class="font-display font-semibold text-kb-text-primary">{{ $plan->title }}</flux:heading>
                                        <flux:text class="text-sm text-kb-text-muted">{{ $plan->timeline_days }} hari</flux:text>
                                    </div>
                                </div>
                                <a href="{{ route('check-in.create', $plan->id) }}">
                                    <flux:button size="xs" variant="subtle">
                                        <flux:icon name="check-circle" class="w-4 h-4 mr-1" />
                                        Tandai Selesai
                                    </flux:button>
                                </a>
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('snapshot.create') }}">
                <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1 card-glow cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-kb-blue-electric/10 border border-kb-blue-electric/15 flex items-center justify-center mb-3">
                        <flux:icon name="arrow-up-tray" class="w-5 h-5 text-kb-blue-light" />
                    </div>
                    <flux:heading level="3" class="font-display font-semibold mb-1 text-kb-text-primary">Input Data Baru</flux:heading>
                    <flux:text class="text-sm text-kb-text-muted">Unggah data penjualan & mulai diagnosis</flux:text>
                </flux:card>
            </a>
            <a href="{{ route('passport.index') }}">
                <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1 card-glow cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-kb-blue-electric/10 border border-kb-blue-electric/15 flex items-center justify-center mb-3">
                        <flux:icon name="user" class="w-5 h-5 text-kb-blue-light" />
                    </div>
                    <flux:heading level="3" class="font-display font-semibold mb-1 text-kb-text-primary">Perbarui Profil</flux:heading>
                    <flux:text class="text-sm text-kb-text-muted">Perbarui profil bisnis Anda</flux:text>
                </flux:card>
            </a>
            <a href="{{ route('action-plan.index') }}">
                <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1 card-glow cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-kb-blue-electric/10 border border-kb-blue-electric/15 flex items-center justify-center mb-3">
                        <flux:icon name="list-bullet" class="w-5 h-5 text-kb-blue-light" />
                    </div>
                    <flux:heading level="3" class="font-display font-semibold mb-1 text-kb-text-primary">Lihat Rencana Aksi</flux:heading>
                    <flux:text class="text-sm text-kb-text-muted">Tinjau rencana aksi Anda</flux:text>
                </flux:card>
            </a>
        </div>
    @else
        {{-- Empty State --}}
        <flux:card class="p-12 text-center rounded-2xl border-kb-border bg-kb-surface-1">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-kb-blue-electric to-kb-blue-light flex items-center justify-center mx-auto mb-4 shadow-brand">
                <flux:icon name="rocket-launch" class="w-8 h-8 text-white" />
            </div>
            <flux:heading level="2" class="font-display text-xl font-bold mb-2 text-kb-text-primary">Selamat Datang di Kawan Bisnis!</flux:heading>
            <flux:text class="text-kb-text-muted mb-6 max-w-md mx-auto">
                Mulai dengan mengisi Profil Bisnis agar AI Growth Team bisa menganalisis bisnis Anda.
            </flux:text>
            <a href="{{ route('passport.index') }}">
                <flux:button variant="primary" class="btn-gradient-primary rounded-xl">Atur Profil Bisnis</flux:button>
            </a>
        </flux:card>
    @endif
</div>
