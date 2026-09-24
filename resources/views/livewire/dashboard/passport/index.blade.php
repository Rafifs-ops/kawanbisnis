<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div>
        <h1 class="font-display text-2xl font-bold text-kb-text-primary">Profil Bisnis</h1>
        <flux:text class="text-kb-text-muted">Identitas dan konteks bisnis Anda untuk AI Growth Team.</flux:text>
    </div>

        @if ($justSavedFirstTime)
            <flux:card class="p-5 rounded-2xl border-emerald-200 bg-emerald-50">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0">
                            <flux:icon name="check-badge" class="w-5 h-5 text-emerald-600" />
                        </div>
                        <div>
                            <flux:heading level="2" class="font-display font-semibold text-kb-text-primary">Profil bisnis berhasil disimpan</flux:heading>
                            <flux:text class="text-sm text-kb-text-muted">Langkah berikutnya: isi data penjualan agar AI dapat menganalisis bisnis Anda.</flux:text>
                        </div>
                    </div>
                    <a href="{{ route('snapshot.create') }}" wire:navigate class="shrink-0">
                        <flux:button variant="primary" class="btn-gradient-primary rounded-xl">
                            Lanjut Isi Data Penjualan
                            <flux:icon name="arrow-right" class="w-4 h-4 ml-1" />
                        </flux:button>
                    </a>
                </div>
            </flux:card>
        @endif

        <form wire:submit="save" class="space-y-8">
            {{-- Profil Bisnis --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <h1 class="font-display text-lg font-semibold text-kb-text-primary">Profil Bisnis</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Nama Bisnis</flux:label>
                        <flux:input wire:model="business_name" placeholder="Contoh: Warung Teh Manis" />
                        <flux:error name="business_name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Jenis Bisnis</flux:label>
                        <flux:select wire:model="business_type">
                            <option value="F&B">F&B (Makanan & Minuman)</option>
                            <option value="Fashion">Fashion</option>
                            <option value="Retail">Retail</option>
                            <option value="Service">Service / Jasa</option>
                        </flux:select>
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Target Customer</flux:label>
                    <flux:input wire:model="target_customer" placeholder="Contoh: Mahasiswa 18-24 tahun" />
                </flux:field>

                <flux:field>
                    <flux:label>Deskripsi Bisnis</flux:label>
                    <flux:textarea wire:model="business_description" rows="3" placeholder="Ceritakan singkat tentang bisnis Anda..." />
                </flux:field>
            </flux:card>

            {{-- Produk --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <div class="flex items-center justify-between">
                    <h1 class="font-display text-lg font-semibold text-kb-text-primary">Produk Utama</h1>
                    <flux:button type="button" variant="subtle" size="xs" wire:click="$set('showAddProductModal', true)">
                        + Tambah Produk
                    </flux:button>
                </div>

                @if (count($products) > 0)
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Nama</flux:table.column>
                            <flux:table.column>Harga</flux:table.column>
                            <flux:table.column>Margin %</flux:table.column>
                            <flux:table.column></flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach ($products as $index => $product)
                                <flux:table.row>
                                    <flux:table.cell>{{ $product['name'] }}</flux:table.cell>
                                    <flux:table.cell>Rp{{ number_format($product['price'], 0, ',', '.') }}</flux:table.cell>
                                    <flux:table.cell>{{ $product['margin'] }}%</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:button type="button" variant="subtle" size="xs" wire:click="removeProduct({{ $index }})" color="red">
                                            Hapus
                                        </flux:button>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @else
                    <flux:text class="text-kb-text-muted text-sm">Belum ada produk. Tambahkan produk utama Anda.</flux:text>
                @endif
            </flux:card>

            {{-- Sales Channels --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <h1 class="font-display text-lg font-semibold text-kb-text-primary">Saluran Penjualan</h1>
                <flux:checkbox.group wire:model="sales_channels">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        @foreach (['Store', 'Instagram', 'Tokopedia', 'Shopee', 'WhatsApp'] as $channel)
                            <flux:checkbox value="{{ $channel }}" label="{{ $channel }}" />
                        @endforeach
                    </div>
                </flux:checkbox.group>
            </flux:card>

            {{-- Constraints --}}
            <flux:card class="p-6 space-y-4 rounded-2xl border-kb-border bg-kb-surface-1">
                <h1 class="font-display text-lg font-semibold text-kb-text-primary">Kendala & Anggaran</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Budget Marketing / bulan (Rp)</flux:label>
                        <flux:input type="number" wire:model="marketing_budget" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Kapasitas Tim (orang)</flux:label>
                        <flux:input type="number" wire:model="team_capacity" />
                    </flux:field>
                </div>
                <p class="text-sm text-kb-text-muted font-medium">Kendala membantu AI memberikan rekomendasi yang realistis sesuai kemampuan Anda.</p>
            </flux:card>

            <div class="flex justify-end">
                <flux:button type="submit" variant="primary" class="btn-gradient-primary rounded-xl">Simpan</flux:button>
            </div>
        </form>

        {{-- Add Product Modal --}}
        <flux:modal wire:model="showAddProductModal">
            <h1 class="font-display">Tambah Produk</h1>
            <div class="mt-4 space-y-4">
                <flux:field>
                    <flux:label>Nama Produk</flux:label>
                    <flux:input wire:model="new_product_name" />
                </flux:field>
                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Harga (Rp)</flux:label>
                        <flux:input type="number" wire:model="new_product_price" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Margin (%)</flux:label>
                        <flux:input type="number" wire:model="new_product_margin" />
                    </flux:field>
                </div>
                <div class="flex justify-end gap-2">
                    <flux:button variant="subtle" wire:click="$set('showAddProductModal', false)">Batal</flux:button>
                    <flux:button variant="primary" wire:click="addProduct" class="btn-gradient-primary">Tambah</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
