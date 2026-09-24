<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
            <h1 class="font-display text-2xl font-bold text-kb-text-primary">Data Snapshot & Target</h1>
            <flux:text class="text-kb-text-muted">Masukkan data penjualan 3 bulan terakhir dan tentukan target bisnis.</flux:text>
        </div>

        <form wire:submit="submit" class="space-y-8">
            {{-- Period --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <h1 class="font-display text-lg font-semibold text-kb-text-primary">Periode Data</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Tanggal Mulai</flux:label>
                        <flux:input type="date" wire:model="period_start" />
                        <flux:error name="period_start" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Tanggal Akhir</flux:label>
                        <flux:input type="date" wire:model="period_end" />
                        <flux:error name="period_end" />
                    </flux:field>
                </div>
            </flux:card>

            {{-- Metrics --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <h1 class="font-display text-lg font-semibold text-kb-text-primary">Metrik Penjualan</h1>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <flux:field>
                        <flux:label>Total Revenue (Rp)</flux:label>
                        <flux:input type="number" wire:model.live="revenue" placeholder="0" />
                        <flux:error name="revenue" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Total Pesanan</flux:label>
                        <flux:input type="number" wire:model.live="total_orders" placeholder="0" />
                        <flux:error name="total_orders" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Rata-rata Nilai Pesanan (Rp)</flux:label>
                        <flux:input type="number" :value="$this->averageOrderValue" readonly class="bg-kb-surface-2 cursor-not-allowed" />
                        <flux:text class="text-xs text-kb-text-muted mt-1">Otomatis: Revenue / Total Pesanan</flux:text>
                    </flux:field>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Pelanggan Baru</flux:label>
                        <flux:input type="number" wire:model="new_customers" placeholder="0" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Pelanggan Lama</flux:label>
                        <flux:input type="number" wire:model="returning_customers" placeholder="0" />
                    </flux:field>
                </div>
            </flux:card>

            {{-- Goal --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <h1 class="font-display text-lg font-semibold text-kb-text-primary">Target Pertumbuhan</h1>
                <flux:text class="text-kb-text-muted text-sm">Pilih target utama yang ingin dicapai.</flux:text>

                <flux:radio.group wire:model.live="goal_type" label="Jenis Target">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <flux:radio value="Increase Sales" label="Naikkan Omzet" />
                        <flux:radio value="Retention" label="Tingkatkan Retensi" />
                        <flux:radio value="AOV" label="Naikkan AOV" />
                        <flux:radio value="Margin" label="Tingkatkan Margin" />
                    </div>
                </flux:radio.group>

                @unless ($this->isTargetInputHidden())
                    <flux:field>
                        @if ($this->targetUnit() === 'percent')
                            <flux:label>Target Margin (%)</flux:label>
                            <flux:input type="number" wire:model.live="target_value" placeholder="25" step="0.01" min="0" max="100" />
                        @elseif ($goal_type === 'AOV')
                            <flux:label>Target AOV (Rp)</flux:label>
                            <flux:input type="number" wire:model.live="target_value" placeholder="0" min="0" />
                        @else
                            <flux:label>Target Omzet (Rp)</flux:label>
                            <flux:input type="number" wire:model.live="target_value" placeholder="0" min="0" />
                        @endif
                        <flux:error name="target_value" />
                    </flux:field>
                @else
                    <flux:text class="text-sm text-kb-text-muted">
                        Retensi pelanggan tidak membutuhkan target angka — AI akan fokus pada churn dan loyalitas.
                    </flux:text>
                @endunless
            </flux:card>

            {{-- Submit --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('dashboard') }}">
                    <flux:button variant="subtle">Batal</flux:button>
                </a>
                <flux:button type="submit" variant="primary" class="btn-gradient-primary rounded-xl" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submit">Mulai Analisis AI</span>
                    <span wire:loading wire:target="submit">Sedang Memproses...</span>
                </flux:button>
            </div>
        </form>
    </div>
