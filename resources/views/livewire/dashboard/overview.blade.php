<div class="flex h-full w-full flex-1 flex-col gap-6">
    {{-- Header with Tujuan Bisnis on the top, beside the title --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="font-display text-2xl font-bold text-kb-text-primary">Dashboard</h1>
                @if ($passport)
                    <p class="text-sm text-kb-text-muted mt-0.5">
                        {{ $passport->business_name }} &middot; {{ $passport->business_type }}
                    </p>
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
                <button type="button"
                    class="btn-gradient-primary rounded-xl px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90 transition-opacity">
                    Atur Profil Bisnis
                </button>
            </a>
        @else
            <a href="{{ route('snapshot.create') }}">
                <button type="button"
                    class="btn-gradient-primary rounded-xl px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90 transition-opacity">
                    Update Data Penjualan
                </button>
            </a>
        @endif
    </div>

    @if ($passport)
        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 md:col-span-2 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
                <span class="text-sm text-kb-text-muted font-medium">Total Revenue</span>
                <div class="kpi-number text-3xl font-bold text-kb-text-primary mt-1">
                    Rp{{ number_format($passport->snapshots()->sum('revenue'), 0, ',', '.') }}
                </div>
            </div>

            <div class="p-5 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
                <span class="text-sm text-kb-text-muted font-medium">Total Pesanan</span>
                <div class="kpi-number text-2xl font-bold text-kb-text-primary mt-1">
                    {{ number_format($passport->snapshots()->sum('total_orders')) }}
                </div>
            </div>
        </div>

        {{-- Timeline Plan Action — horizontal roadmap (left → right) --}}
        @if (count($timelineSteps) > 0)
            <div class="p-5 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display text-lg font-semibold text-kb-text-primary">
                        Timeline Plan Action
                    </h2>
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
                                            {{ $step['title'] }}
                                        </div>
                                        <div class="text-[10px] text-kb-text-muted mt-0.5">
                                            {{ \Carbon\Carbon::parse($step['date'])->format('d M Y') }}
                                        </div>
                                        <div class="mt-1">
                                            @if ($step['status'] === 'done')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-800">
                                                    Selesai
                                                </span>
                                            @elseif ($step['status'] === 'today')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800">
                                                    Hari Ini
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700">
                                                    Berikutnya
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Riwayat Diagnosis Terbaru — max 3 cards, column layout, newest first --}}
        @if ($recentDiagnoses->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display text-lg font-semibold text-kb-text-primary">
                        Riwayat Diagnosis
                    </h2>
                    <a href="{{ route('diagnosis.history') }}"
                        class="text-sm font-medium text-kb-blue-electric hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-3">
                    @foreach ($recentDiagnoses as $diagnosis)
                        <a href="{{ route('diagnosis.show', $diagnosis) }}" wire:navigate class="block">
                            <div
                                class="p-4 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm hover:shadow-md transition-shadow cursor-pointer card-glow">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span
                                                class="text-xs font-semibold text-kb-text-muted uppercase tracking-wide">
                                                {{ $diagnosis->growthGoal->goal_type }}
                                            </span>
                                            @if ($diagnosis->status === 'completed')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    Selesai
                                                </span>
                                            @elseif ($diagnosis->status === 'processing')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                                    Sedang Diproses
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Gagal
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-kb-text-muted">
                                            {{ $diagnosis->created_at->format('d M Y') }}
                                        </p>
                                        @if ($diagnosis->status === 'completed' && ($diagnosis->business_diagnosis || $diagnosis->summary_diagnosis))
                                            <p class="text-sm text-kb-text-secondary mt-1.5 line-clamp-2">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($diagnosis->business_diagnosis ?? $diagnosis->summary_diagnosis), 140) }}
                                            </p>
                                        @elseif ($diagnosis->status === 'processing')
                                            <p class="text-sm text-kb-text-muted mt-1.5">
                                                Proses berjalan — buka untuk melihat detail.
                                            </p>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-kb-text-faint shrink-0 mt-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        {{-- Empty State --}}
        <div class="p-12 text-center rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
            <div
                class="w-16 h-16 rounded-2xl bg-kb-blue-electric flex items-center justify-center mx-auto mb-4 shadow-brand">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699-2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m2.699 2.7a6.003 6.003 0 00-2.699-2.7" />
                </svg>
            </div>
            <h2 class="font-display text-xl font-bold mb-2 text-kb-text-primary">
                Selamat Datang di Kawan Bisnis!
            </h2>
            <p class="text-kb-text-muted mb-6 max-w-md mx-auto text-sm">
                Mulai dengan mengisi Profil Bisnis agar AI Growth Team bisa menganalisis bisnis Anda.
            </p>
            <a href="{{ route('passport.index') }}">
                <button type="button"
                    class="btn-gradient-primary rounded-xl px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:opacity-90 transition-opacity">
                    Atur Profil Bisnis
                </button>
            </a>
        </div>
    @endif
</div>
