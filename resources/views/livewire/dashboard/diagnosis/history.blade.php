<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <h1 class="font-display text-2xl font-bold text-kb-text-primary">Riwayat Diagnosis</h1>
        <flux:text class="text-kb-text-muted">Semua diagnosis AI Growth Team, termasuk yang sedang berjalan.</flux:text>
    </div>

    @if ($diagnoses->count() > 0)
        <div class="space-y-4">
            @foreach ($diagnoses as $diagnosis)
                <a href="{{ route('diagnosis.show', $diagnosis) }}" wire:navigate class="block">
                    <flux:card class="p-5 rounded-2xl border-kb-border bg-kb-surface-1 card-glow cursor-pointer">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <flux:heading level="3" class="font-display font-semibold text-kb-text-primary">
                                        {{ $diagnosis->growthGoal->goal_type }}
                                    </flux:heading>
                                    @if ($diagnosis->status === 'completed')
                                        <flux:badge variant="soft" color="green" size="xs">Selesai</flux:badge>
                                    @elseif ($diagnosis->status === 'processing')
                                        <flux:badge variant="soft" color="yellow" size="xs">Sedang Diproses</flux:badge>
                                    @else
                                        <flux:badge variant="soft" color="red" size="xs">Gagal</flux:badge>
                                    @endif
                                </div>
                                <flux:text class="text-sm text-kb-text-muted">
                                    {{ $diagnosis->created_at->format('d M Y H:i') }}
                                    &middot; {{ $diagnosis->actionPlans->count() }} rencana aksi
                                </flux:text>
                                @if ($diagnosis->status === 'completed' && ($diagnosis->business_diagnosis || $diagnosis->summary_diagnosis))
                                    <flux:text class="text-sm text-kb-text-secondary mt-2 block line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($diagnosis->business_diagnosis ?? $diagnosis->summary_diagnosis), 160) }}
                                    </flux:text>
                                @elseif ($diagnosis->status === 'processing')
                                    <flux:text class="text-sm text-kb-text-muted mt-2 block">
                                        Buka untuk melihat proses analisis agen secara langsung.
                                    </flux:text>
                                @endif
                            </div>
                            <flux:icon name="chevron-right" class="w-5 h-5 text-kb-text-faint shrink-0 mt-1" />
                        </div>
                    </flux:card>
                </a>
            @endforeach
        </div>
    @else
        <flux:card class="p-12 text-center rounded-2xl border-kb-border bg-kb-surface-1">
            <div class="w-16 h-16 rounded-2xl bg-kb-blue-electric/10 border border-kb-blue-electric/15 flex items-center justify-center mx-auto mb-4">
                <flux:icon name="clock" class="w-8 h-8 text-kb-blue-electric/50" />
            </div>
            <flux:heading level="2" class="font-display text-xl font-semibold mb-2 text-kb-text-primary">Belum ada diagnosis</flux:heading>
            <flux:text class="text-kb-text-muted mb-6">
                Jalankan diagnosis pertama Anda untuk melihat riwayatnya di sini.
            </flux:text>
            <a href="{{ route('snapshot.create') }}">
                <flux:button variant="primary" class="btn-gradient-primary rounded-xl">Mulai Diagnosis</flux:button>
            </a>
        </flux:card>
    @endif
</div>
