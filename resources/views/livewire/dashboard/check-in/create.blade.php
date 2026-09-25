<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading level="1" class="font-display text-2xl font-bold text-kb-text-primary">Tandai Selesai</flux:heading>
            <flux:text class="text-kb-text-muted">
                Laporkan hasil eksekusi action plan: {{ $actionPlan->title }}
            </flux:text>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-kb-blue-electric hover:underline">
            Kembali
        </a>
    </div>

    {{-- Action Plan Info --}}
    <flux:card class="p-4 rounded-2xl border-kb-border bg-kb-surface-1">
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-full bg-gradient-to-br from-kb-blue-electric to-kb-blue-light text-white flex items-center justify-center text-sm font-bold">
                {{ $actionPlan->priority_rank }}
            </div>
            <div>
                <flux:heading level="3" class="font-display font-semibold text-kb-text-primary">
                    {{ $actionPlan->title }}</flux:heading>
                <flux:text class="text-sm text-kb-text-muted">
                    {{ $actionPlan->timeline_days }} hari &middot;
                    Target KPI: {{ $actionPlan->target_kpi['metric'] ?? '-' }}
                </flux:text>
            </div>
        </div>
    </flux:card>

    @if ($feedback)
        {{-- Completed Check-in Feedback (read-only) --}}
        <flux:card class="p-6 rounded-2xl border-kb-border bg-kb-surface-1">
            <div class="flex items-center gap-2 mb-4">
                <flux:badge variant="soft" color="green">
                    <flux:icon name="check-circle" class="w-4 h-4 mr-1" />
                    Sudah Ditandai Selesai
                </flux:badge>
            </div>

            <div class="space-y-5">
                <div>
                    <flux:heading level="3" class="text-xs font-semibold text-kb-text-muted uppercase tracking-wider mb-1">Tanggal Eksekusi</flux:heading>
                    <div class="text-sm font-medium text-kb-text-primary">
                        {{ $feedback->checkin_date?->format('d M Y') ?? '-' }}
                    </div>
                </div>

                <div>
                    <flux:heading level="3" class="text-xs font-semibold text-kb-text-muted uppercase tracking-wider mb-1">Hasil Aktual</flux:heading>
                    <div class="text-sm text-kb-text-secondary leading-relaxed">
                        {{ $feedback->actual_result['result'] ?? '-' }}
                    </div>
                </div>

                <div>
                    <flux:heading level="3" class="text-xs font-semibold text-kb-text-muted uppercase tracking-wider mb-1">KPI Tercapai?</flux:heading>
                    <flux:badge variant="soft" :color="($feedback->kpi_achieved['achieved'] ?? false) ? 'green' : 'red'">
                        {{ ($feedback->kpi_achieved['achieved'] ?? false) ? 'Ya' : 'Belum' }}
                        @if (! empty($feedback->kpi_achieved['metric']))
                            &middot; {{ $feedback->kpi_achieved['metric'] }}
                        @endif
                    </flux:badge>
                </div>

                <div>
                    <flux:heading level="3" class="text-xs font-semibold text-kb-text-muted uppercase tracking-wider mb-1">Catatan Belajar</flux:heading>
                    <div class="text-sm text-kb-text-secondary leading-relaxed">
                        {{ $feedback->learning_notes ?? '-' }}
                    </div>
                </div>
            </div>
        </flux:card>

        <div class="flex justify-end gap-3">
            <a href="{{ route('action-plan.index') }}">
                <flux:button variant="subtle">Kembali ke Action Plan</flux:button>
            </a>
        </div>
    @else
        <form wire:submit="submit" class="space-y-6">
            {{-- Tanggal Eksekusi --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Tanggal Eksekusi</flux:heading>
                <flux:field>
                    <flux:input type="date" wire:model="checkin_date" />
                    <flux:error name="checkin_date" />
                </flux:field>
            </flux:card>

            {{-- Hasil Aktual --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Hasil Aktual</flux:heading>
                <flux:text class="text-kb-text-muted text-sm">
                    Ceritakan apa yang benar-benar terjadi setelah menjalankan action plan.
                </flux:text>
                <flux:field>
                    <flux:textarea wire:model="actual_result" rows="4"
                        placeholder="Contoh: Setelah menjalankan action plan selama 7 hari, penjualan naik 15% dari Rp5jt ke Rp5.75jt..." />
                    <flux:error name="actual_result" />
                </flux:field>
            </flux:card>

            {{-- KPI Tercapai --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">KPI Tercapai?</flux:heading>
                <flux:switch wire:model="kpi_achieved" label="Ya, target KPI tercapai" />
            </flux:card>

            {{-- Catatan Belajar --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <flux:heading level="2" class="font-display text-lg font-semibold text-kb-text-primary">Catatan Belajar</flux:heading>
                <flux:text class="text-kb-text-muted text-sm">
                    Apa yang dipelajari dari eksekusi ini? Apa yang akan dilakukan berbeda?
                </flux:text>
                <flux:field>
                    <flux:textarea wire:model="learning_notes" rows="3"
                        placeholder="Contoh: Ternyata fokus di Instagram lebih efektif daripada buka di Shopee karena target customer lebih aktif di IG..." />
                </flux:field>
            </flux:card>

            {{-- Submit --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('action-plan.index') }}">
                    <flux:button variant="subtle">Batal</flux:button>
                </a>
                <flux:button type="submit" variant="primary" class="btn-gradient-primary rounded-xl">
                    Simpan
                </flux:button>
            </div>
        </form>
    @endif
</div>
