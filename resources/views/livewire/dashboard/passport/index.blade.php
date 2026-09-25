<div class="flex h-full w-full flex-1 flex-col gap-6" x-data="{ showAddProductModal: @entangle('showAddProductModal') }">
    <div>
        <h1 class="font-display text-2xl font-bold text-kb-text-primary">Profil Bisnis</h1>
        <p class="text-sm text-kb-text-muted mt-1">Identitas dan konteks bisnis Anda untuk AI Growth Team.</p>
    </div>

    @if ($justSavedFirstTime)
        <div class="p-5 rounded-2xl border border-emerald-200 bg-emerald-50">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-display font-semibold text-kb-text-primary text-base">Profil bisnis berhasil
                            disimpan</h2>
                        <p class="text-sm text-kb-text-muted mt-0.5">Langkah berikutnya: isi data penjualan agar AI
                            dapat menganalisis bisnis Anda.</p>
                    </div>
                </div>
                <a href="{{ route('snapshot.create') }}" wire:navigate class="shrink-0">
                    <button type="button"
                        class="btn-gradient-primary rounded-xl px-4 py-2.5 text-sm font-medium text-white inline-flex items-center gap-1.5 shadow-sm hover:opacity-90 transition-opacity">
                        <span>Lanjut Isi Data Penjualan</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </a>
            </div>
        </div>
    @endif

    <form wire:submit="save" class="space-y-8">
        {{-- Profil Bisnis --}}
        <div class="p-6 space-y-4 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
            <h2 class="font-display text-lg font-semibold text-kb-text-primary">Profil Bisnis</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-kb-text-primary">Nama Bisnis</label>
                    <input type="text" wire:model="business_name" placeholder="Contoh: Warung Teh Manis"
                        class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
                    @error('business_name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-kb-text-primary">Jenis Bisnis</label>
                    <select wire:model="business_type"
                        class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors">
                        <option value="F&B">F&B (Makanan & Minuman)</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Retail">Retail</option>
                        <option value="Service">Service / Jasa</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-kb-text-primary">Target Customer</label>
                <input type="text" wire:model="target_customer" placeholder="Contoh: Mahasiswa 18-24 tahun"
                    class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-kb-text-primary">Deskripsi Bisnis</label>
                <textarea wire:model="business_description" rows="3" placeholder="Ceritakan singkat tentang bisnis Anda..."
                    class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors resize-y"></textarea>
            </div>
        </div>

        {{-- Produk --}}
        <div class="p-6 space-y-4 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-lg font-semibold text-kb-text-primary">Produk Utama</h2>
                <button type="button" wire:click="$set('showAddProductModal', true)"
                    class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-200">
                    + Tambah Produk
                </button>
            </div>

            @if (count($products) > 0)
                <div class="overflow-x-auto border border-gray-200 rounded-xl">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Harga</th>
                                <th class="px-4 py-3">Margin %</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($products as $index => $product)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $product['name'] }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        Rp{{ number_format($product['price'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $product['margin'] }}%</td>
                                    <td class="px-4 py-3 text-right">
                                        <button type="button" wire:click="removeProduct({{ $index }})"
                                            class="px-2.5 py-1 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-kb-text-muted text-sm">Belum ada produk. Tambahkan produk utama Anda.</p>
            @endif
        </div>

        {{-- Sales Channels --}}
        <div class="p-6 space-y-4 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
            <h2 class="font-display text-lg font-semibold text-kb-text-primary">Saluran Penjualan</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                @foreach (['Store', 'Instagram', 'Tokopedia', 'Shopee', 'WhatsApp'] as $channel)
                    <label
                        class="flex items-center gap-2.5 p-3 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50/30 cursor-pointer transition-all select-none">
                        <input type="checkbox" value="{{ $channel }}" wire:model="sales_channels"
                            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                        <span class="text-sm font-medium text-gray-800">{{ $channel }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Constraints --}}
        <div class="p-6 space-y-4 rounded-2xl border border-kb-border bg-kb-surface-1 shadow-sm">
            <h2 class="font-display text-lg font-semibold text-kb-text-primary">Anggaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-kb-text-primary">Budget Marketing / bulan (Rp)</label>
                    <input type="number" wire:model="marketing_budget"
                        class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-kb-text-primary">Kapasitas Tim (orang)</label>
                    <input type="number" wire:model="team_capacity"
                        class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="btn-gradient-primary rounded-xl px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-90 transition-opacity">
                Simpan
            </button>
        </div>
    </form>

    {{-- Add Product Modal --}}
    <div x-show="showAddProductModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
        <div @click.away="showAddProductModal = false"
            class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-6 space-y-4"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <h3 class="font-display text-lg font-bold text-gray-900">Tambah Produk</h3>

            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" wire:model="new_product_name"
                        class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" wire:model="new_product_price"
                            class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-gray-700">Margin (%)</label>
                        <input type="number" wire:model="new_product_margin"
                            class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors" />
                    </div>
                </div>

                <div class="flex justify-end gap-2.5 pt-2">
                    <button type="button" wire:click="$set('showAddProductModal', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="button" wire:click="addProduct"
                        class="btn-gradient-primary px-4 py-2 text-sm font-semibold text-white rounded-xl shadow-sm hover:opacity-90 transition-opacity">
                        Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
