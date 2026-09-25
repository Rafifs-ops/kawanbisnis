<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <h1 class="font-display text-2xl font-bold text-kb-text-primary">Data Snapshot & Target</h1>
        <p class="text-kb-text-muted text-sm mt-1">Masukkan data penjualan 3 bulan terakhir dan tentukan target bisnis.
        </p>
    </div>

    <form wire:submit="submit" class="space-y-8">
        {{-- Period --}}
        <div class="p-6 space-y-4 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
            <h2 class="font-display text-lg font-semibold text-kb-text-primary">Periode Data</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Tanggal Mulai</label>
                    <input type="date" wire:model="period_start"
                        class="w-full rounded-xl border border-kb-border bg-white px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm focus:border-kb-blue-electric focus:outline-none focus:ring-1 focus:ring-kb-blue-electric" />
                    @error('period_start')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Tanggal Akhir</label>
                    <input type="date" wire:model="period_end"
                        class="w-full rounded-xl border border-kb-border bg-white px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm focus:border-kb-blue-electric focus:outline-none focus:ring-1 focus:ring-kb-blue-electric" />
                    @error('period_end')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Metrics --}}
        <div class="p-6 space-y-4 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
            <h2 class="font-display text-lg font-semibold text-kb-text-primary">Metrik Penjualan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Total Revenue (Rp)</label>
                    <input type="number" wire:model.live="revenue" placeholder="0"
                        class="w-full rounded-xl border border-kb-border bg-white px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm focus:border-kb-blue-electric focus:outline-none focus:ring-1 focus:ring-kb-blue-electric" />
                    @error('revenue')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Total Pesanan</label>
                    <input type="number" wire:model.live="total_orders" placeholder="0"
                        class="w-full rounded-xl border border-kb-border bg-white px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm focus:border-kb-blue-electric focus:outline-none focus:ring-1 focus:ring-kb-blue-electric" />
                    @error('total_orders')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Rata-rata Nilai Pesanan
                        (Rp)</label>
                    <input type="number" value="{{ $this->averageOrderValue }}" readonly
                        class="w-full rounded-xl border border-kb-border bg-kb-surface-2 px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm cursor-not-allowed opacity-80" />
                    <p class="text-xs text-kb-text-muted mt-1.5">Otomatis: Revenue / Total Pesanan</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Pelanggan Baru</label>
                    <input type="number" wire:model="new_customers" placeholder="0"
                        class="w-full rounded-xl border border-kb-border bg-white px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm focus:border-kb-blue-electric focus:outline-none focus:ring-1 focus:ring-kb-blue-electric" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Pelanggan Lama</label>
                    <input type="number" wire:model="returning_customers" placeholder="0"
                        class="w-full rounded-xl border border-kb-border bg-white px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm focus:border-kb-blue-electric focus:outline-none focus:ring-1 focus:ring-kb-blue-electric" />
                </div>
            </div>
        </div>

        {{-- Goal --}}
        <div class="p-6 space-y-4 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
            <div>
                <h2 class="font-display text-lg font-semibold text-kb-text-primary">Target Pertumbuhan</h2>
                <p class="text-kb-text-muted text-sm mt-0.5">Pilih target utama yang ingin dicapai.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-kb-text-primary mb-2">Jenis Target</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label
                        class="relative flex items-center justify-center p-3.5 rounded-xl border border-kb-border bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-kb-blue-electric has-[:checked]:ring-1 has-[:checked]:ring-kb-blue-electric has-[:checked]:bg-blue-200">
                        <input type="radio" wire:model.live="goal_type" value="Increase Sales" class="sr-only" />
                        <span class="text-sm font-medium text-kb-text-primary">Naikkan Omzet</span>
                    </label>

                    <label
                        class="relative flex items-center justify-center p-3.5 rounded-xl border border-kb-border bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-kb-blue-electric has-[:checked]:ring-1 has-[:checked]:ring-kb-blue-electric has-[:checked]:bg-blue-200">
                        <input type="radio" wire:model.live="goal_type" value="Retention" class="sr-only" />
                        <span class="text-sm font-medium text-kb-text-primary">Tingkatkan Retensi</span>
                    </label>

                    <label
                        class="relative flex items-center justify-center p-3.5 rounded-xl border border-kb-border bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-kb-blue-electric has-[:checked]:ring-1 has-[:checked]:ring-kb-blue-electric has-[:checked]:bg-blue-200">
                        <input type="radio" wire:model.live="goal_type" value="AOV" class="sr-only" />
                        <span class="text-sm font-medium text-kb-text-primary">Naikkan AOV</span>
                    </label>

                    <label
                        class="relative flex items-center justify-center p-3.5 rounded-xl border border-kb-border bg-white shadow-sm cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-kb-blue-electric has-[:checked]:ring-1 has-[:checked]:ring-kb-blue-electric has-[:checked]:bg-blue-200">
                        <input type="radio" wire:model.live="goal_type" value="Margin" class="sr-only" />
                        <span class="text-sm font-medium text-kb-text-primary">Tingkatkan Margin</span>
                    </label>
                </div>
            </div>

            @unless ($this->isTargetInputHidden())
                <div>
                    @if ($this->targetUnit() === 'percent')
                        <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Target Margin (%)</label>
                        <input type="number" wire:model.live="target_value" placeholder="25" step="0.01" min="0"
                            max="100"
                            class="w-full rounded-xl border border-kb-border bg-white px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm focus:border-kb-blue-electric focus:outline-none focus:ring-1 focus:ring-kb-blue-electric" />
                    @elseif ($goal_type === 'AOV')
                        <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Target AOV (Rp)</label>
                        <input type="number" wire:model.live="target_value" placeholder="0" min="0"
                            class="w-full rounded-xl border border-kb-border bg-white px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm focus:border-kb-blue-electric focus:outline-none focus:ring-1 focus:ring-kb-blue-electric" />
                    @else
                        <label class="block text-sm font-medium text-kb-text-primary mb-1.5">Target Omzet (Rp)</label>
                        <input type="number" wire:model.live="target_value" placeholder="0" min="0"
                            class="w-full rounded-xl border border-kb-border bg-white px-3.5 py-2.5 text-sm text-kb-text-primary shadow-sm focus:border-kb-blue-electric focus:outline-none focus:ring-1 focus:ring-kb-blue-electric" />
                    @endif
                    @error('target_value')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <p class="text-sm text-kb-text-muted">
                    Retensi pelanggan tidak membutuhkan target angka — AI akan fokus pada churn dan loyalitas.
                </p>
            @endunless
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard') }}">
                <button type="button"
                    class="px-4 py-2.5 rounded-xl border border-kb-border bg-white text-sm font-medium text-kb-text-primary shadow-sm hover:bg-gray-50 transition-colors">
                    Batal
                </button>
            </a>
            <button type="submit"
                class="btn-gradient-primary px-4 py-2.5 rounded-xl text-sm font-medium text-white shadow-sm hover:opacity-90 disabled:opacity-50 transition-all"
                wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">Mulai Analisis AI</span>
                <span wire:loading wire:target="submit">Sedang Memproses...</span>
            </button>
        </div>
    </form>
</div>
