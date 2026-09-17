<div>
    {{-- Hero Section — Pipeline Visualization --}}
    <section class="relative overflow-hidden bg-kb-surface-0 text-kb-text-primary">
        {{-- Ambient glow --}}
        <div class="absolute inset-0" style="background: var(--gradient-kb-glow);"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-24">
            <div class="text-center">
                <div class="reveal mb-2">
                    <x-app-logo-icon class="mx-auto h-30 w-auto" />
                </div>

                <flux:heading level="1" class="font-display text-4xl lg:text-6xl font-bold mb-6 text-kb-text-primary leading-[1.1] tracking-tight">
                    <span class="word-reveal" style="animation-delay: 0.1s">Tim</span>
                    <span class="word-reveal" style="animation-delay: 0.15s">AI</span>
                    <span class="word-reveal" style="animation-delay: 0.2s">untuk</span>
                    <br class="hidden sm:block" />
                    <span class="word-reveal" style="animation-delay: 0.3s;">Pertumbuhan</span>
                    <span class="word-reveal" style="animation-delay: 0.35s">UMKM</span>
                </flux:heading>

                <div class="reveal reveal-delay-2">
                    <flux:text class="text-lg text-kb-text-secondary mb-10 max-w-2xl mx-auto leading-relaxed">
                        4 agen AI menganalisis bisnis Anda secara menyeluruh — dari data, pelanggan, marketing, hingga strategi — lalu menghasilkan diagnosis dan rencana aksi prioritas.
                    </flux:text>
                </div>

                {{-- Pipeline Visualization --}}
                <div class="reveal reveal-delay-3 mb-10">
                    <div class="inline-flex items-center gap-2 sm:gap-3 px-6 py-4 rounded-2xl glass">
                        {{-- Analytics Node --}}
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center">
                                <flux:icon name="chart-bar" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-text-muted font-medium">Analytics</span>
                        </div>

                        <div class="w-6 sm:w-10 h-px bg-gradient-to-r from-blue-500/40 to-emerald-500/40 hidden sm:block"></div>

                        {{-- Customer Node --}}
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center">
                                <flux:icon name="users" class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-400" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-text-muted font-medium">Customer</span>
                        </div>

                        <div class="w-6 sm:w-10 h-px bg-gradient-to-r from-emerald-500/40 to-amber-500/40 hidden sm:block"></div>

                        {{-- Marketing Node --}}
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center">
                                <flux:icon name="megaphone" class="w-5 h-5 sm:w-6 sm:h-6 text-amber-400" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-text-muted font-medium">Marketing</span>
                        </div>

                        <div class="w-6 sm:w-10 h-px bg-gradient-to-r from-amber-500/40 to-rose-500/40 hidden sm:block"></div>

                        {{-- Strategy Node --}}
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-rose-500/15 border border-rose-500/25 flex items-center justify-center">
                                <flux:icon name="light-bulb" class="w-5 h-5 sm:w-6 sm:h-6 text-rose-400" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-text-muted font-medium">Strategy</span>
                        </div>

                        <div class="w-6 sm:w-10 h-px bg-gradient-to-r from-rose-500/40 to-kb-blue-light/60 hidden sm:block"></div>

                        {{-- Output: Diagnosis --}}
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-kb-blue-electric to-kb-blue-light flex items-center justify-center shadow-brand">
                                <flux:icon name="sparkles" class="w-5 h-5 sm:w-6 sm:h-6 text-white" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-blue-light font-semibold">Diagnosis</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 justify-center reveal reveal-delay-4">
                    @auth
                        <a href="{{ route('dashboard') }}">
                            <flux:button variant="primary" class="btn-gradient-primary text-base py-2.5 px-6 rounded-xl font-semibold">
                                Buka Dashboard
                            </flux:button>
                        </a>
                    @else
                        <a href="{{ route('register') }}">
                            <flux:button variant="primary" class="btn-gradient-primary text-base py-2.5 px-6 rounded-xl font-semibold">
                                Mulai Gratis
                            </flux:button>
                        </a>
                        <a href="{{ route('login') }}">
                            <flux:button variant="subtle" class="text-kb-text-secondary border-kb-border hover:bg-kb-surface-2 text-base py-2.5 px-6 rounded-xl font-semibold transition-all duration-300">
                                Masuk
                            </flux:button>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Problem Framework — Varied Visual Weight --}}
    <section class="py-20 bg-kb-surface-0">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="reveal">
                    <h1 class="font-display text-3xl font-bold mb-4 text-kb-text-primary">Masalah yang Dihadapi UMKM</h1>
                </div>
                <div class="reveal reveal-delay-1">
                    <p class="text-kb-text-muted max-w-2xl mx-auto">
                        6 pilar utama yang menghambat pertumbuhan bisnis kecil dan menengah.
                    </p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                {{-- Large feature card --}}
                <div class="reveal md:col-span-2 md:row-span-2">
                    <div class="h-full p-8 rounded-2xl border border-kb-border bg-kb-surface-1 card-glow">
                        <div class="w-14 h-14 rounded-2xl bg-kb-blue-electric/15 border border-kb-blue-electric/20 flex items-center justify-center mb-6">
                            <flux:icon name="chart-bar" class="w-7 h-7 text-kb-blue-light" />
                        </div>
                        <flux:heading level="3" class="font-display text-2xl font-bold mb-3 text-kb-text-primary">Data Terfragmentasi</flux:heading>
                        <flux:text class="text-kb-text-secondary text-base leading-relaxed">
                            Data penjualan tersebar di Excel, WhatsApp, catatan manual. Tidak ada satu sumber kebenaran yang bisa diandalkan untuk pengambilan keputusan.
                        </flux:text>
                    </div>
                </div>

                {{-- Standard cards --}}
                @foreach([
                    ['icon' => 'users', 'title' => 'Kurangnya Insight Pelanggan', 'desc' => 'Tidak tahu siapa pelanggan setia dan siapa yang churn.'],
                    ['icon' => 'megaphone', 'title' => 'Marketing Tanpa Target', 'desc' => 'Budget iklan habis tanpa ROI yang jelas.'],
                ] as $i => $problem)
                    <div class="reveal reveal-delay-{{ $i + 1 }}">
                        <div class="h-full p-6 rounded-2xl border border-kb-border bg-kb-surface-1 card-glow">
                            <div class="w-11 h-11 rounded-xl bg-kb-blue-electric/10 border border-kb-blue-electric/15 flex items-center justify-center mb-4">
                                <flux:icon name="{{ $problem['icon'] }}" class="w-5 h-5 text-kb-blue-light" />
                            </div>
                            <flux:heading level="3" class="font-display text-lg font-semibold mb-2 text-kb-text-primary">{{ $problem['title'] }}</flux:heading>
                            <flux:text class="text-kb-text-muted text-sm leading-relaxed">{{ $problem['desc'] }}</flux:text>
                        </div>
                    </div>
                @endforeach

                {{-- Compact cards --}}
                @foreach([
                    ['icon' => 'cog', 'title' => 'Operasional Manual', 'desc' => 'Proses manual menghabiskan waktu produktif.'],
                    ['icon' => 'banknotes', 'title' => 'Cash Flow Sulit Diprediksi', 'desc' => 'Omzet naik-turun tanpa pola yang bisa dipahami.'],
                    ['icon' => 'clock', 'title' => 'Tidak Ada Waktu untuk Growth', 'desc' => 'Sibuk operasional, tidak ada waktu untuk strategi.'],
                ] as $i => $problem)
                    <div class="reveal reveal-delay-{{ ($i % 3) + 1 }}">
                        <div class="h-full p-5 rounded-xl border border-kb-border-subtle bg-kb-surface-0">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-kb-blue-electric/8 flex items-center justify-center">
                                    <flux:icon name="{{ $problem['icon'] }}" class="w-4 h-4 text-kb-blue-light/70" />
                                </div>
                                <flux:heading level="3" class="font-display text-sm font-semibold text-kb-text-primary">{{ $problem['title'] }}</flux:heading>
                            </div>
                            <flux:text class="text-kb-text-muted text-xs leading-relaxed pl-11">{{ $problem['desc'] }}</flux:text>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AI Team Preview — Distinct Agent Identity --}}
    <section class="py-20 bg-kb-surface-1 section-glow">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="reveal">
                    <h1 class="font-display text-3xl font-bold mb-4 text-kb-text-primary">4 Agen AI yang Bekerja untuk Anda</h1>
                </div>
                <div class="reveal reveal-delay-1">
                    <p class="text-kb-text-muted max-w-2xl mx-auto">
                        Setiap agen memiliki keahlian spesifik. Bersama-sama, mereka menganalisis bisnis Anda secara menyeluruh.
                    </p>
                </div>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $agents = [
                        ['name' => 'Analytics Agent', 'icon' => 'chart-bar', 'desc' => 'Menganalisis angka, tren, dan anomali dari data bisnis.', 'color' => 'blue', 'gradient' => 'from-blue-500/20 to-blue-600/5', 'border' => 'border-blue-500/20', 'iconBg' => 'bg-blue-500/15', 'iconColor' => 'text-blue-400', 'tagColor' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                        ['name' => 'Customer Agent', 'icon' => 'users', 'desc' => 'Memahami perilaku pelanggan, retensi, dan churn.', 'color' => 'emerald', 'gradient' => 'from-emerald-500/20 to-emerald-600/5', 'border' => 'border-emerald-500/20', 'iconBg' => 'bg-emerald-500/15', 'iconColor' => 'text-emerald-400', 'tagColor' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'],
                        ['name' => 'Marketing Agent', 'icon' => 'megaphone', 'desc' => 'Mengevaluasi efektivitas channel pemasaran.', 'color' => 'amber', 'gradient' => 'from-amber-500/20 to-amber-600/5', 'border' => 'border-amber-500/20', 'iconBg' => 'bg-amber-500/15', 'iconColor' => 'text-amber-400', 'tagColor' => 'bg-amber-500/10 text-amber-400 border-amber-500/20'],
                        ['name' => 'Strategy Agent', 'icon' => 'light-bulb', 'desc' => 'Mensintesis semua temuan jadi rencana aksi prioritas.', 'color' => 'rose', 'gradient' => 'from-rose-500/20 to-rose-600/5', 'border' => 'border-rose-500/20', 'iconBg' => 'bg-rose-500/15', 'iconColor' => 'text-rose-400', 'tagColor' => 'bg-rose-500/10 text-rose-400 border-rose-500/20'],
                    ];
                @endphp

                @foreach ($agents as $i => $agent)
                    <div class="reveal reveal-delay-{{ $i + 1 }}">
                        <div class="h-full p-6 rounded-2xl border {{ $agent['border'] }} bg-gradient-to-br {{ $agent['gradient'] }} card-glow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-11 h-11 rounded-xl {{ $agent['iconBg'] }} flex items-center justify-center">
                                    <flux:icon name="{{ $agent['icon'] }}" class="w-5 h-5 {{ $agent['iconColor'] }}" />
                                </div>
                                <flux:badge variant="soft" size="sm" class="{{ $agent['tagColor'] }}">{{ $agent['name'] }}</flux:badge>
                            </div>
                            <flux:text class="text-kb-text-secondary text-sm leading-relaxed">{{ $agent['desc'] }}</flux:text>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works — 6 Steps with Numbered Markers --}}
    <section class="py-20 bg-kb-surface-0">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="reveal">
                    <h1 class="font-display text-3xl font-bold mb-4 text-kb-text-primary">Cara Kerja</h1>
                </div>
                <div class="reveal reveal-delay-1">
                    <p class="text-kb-text-muted max-w-2xl mx-auto">
                        Siklus pertumbuhan yang berkelanjutan untuk bisnis Anda.
                    </p>
                </div>
            </div>
            <div class="grid md:grid-cols-6 gap-6">
                @foreach([
                    ['step' => '01', 'title' => 'Isi Business Passport', 'desc' => 'Profil bisnis, produk, channel'],
                    ['step' => '02', 'title' => 'Upload Data Penjualan', 'desc' => 'Data 3 bulan terakhir'],
                    ['step' => '03', 'title' => 'AI Menganalisis', 'desc' => '4 agen bekerja menganalisis'],
                    ['step' => '04', 'title' => 'Dapat Diagnosis', 'desc' => 'Masalah, akar masalah, peluang'],
                    ['step' => '05', 'title' => 'Action Plan Prioritas', 'desc' => 'Top 3 aksi 7-30 hari'],
                    ['step' => '06', 'title' => 'Tandai Selesai & Evaluasi', 'desc' => 'Hasil → knowledge AI → siklus berikutnya'],
                ] as $i => $item)
                    <div class="text-center reveal reveal-delay-{{ ($i % 6) + 1 }}">
                        <div class="w-14 h-14 rounded-2xl bg-kb-surface-2 border border-kb-border flex items-center justify-center mx-auto mb-4">
                            <span class="kpi-number text-lg text-kb-blue-light">{{ $item['step'] }}</span>
                        </div>
                        <flux:heading level="3" class="font-display text-sm font-semibold mb-1 text-kb-text-primary">{{ $item['title'] }}</flux:heading>
                        <flux:text class="text-kb-text-muted text-xs">{{ $item['desc'] }}</flux:text>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Banner --}}
    <section class="py-20 bg-kb-surface-0 relative overflow-hidden section-glow">
        <div class="absolute inset-0" style="background: var(--gradient-kb-glow);"></div>

        <div class="relative max-w-4xl mx-auto px-6 text-center">
            <div class="reveal">
                <h1 class="font-display text-3xl font-bold mb-4 text-kb-text-primary">
                    Siap Memulai Pertumbuhan?
                </h1>
            </div>
            <div class="reveal reveal-delay-1">
                <flux:text class="text-kb-text-secondary mb-8 text-lg">
                    Mulai diagnosis gratis sekarang. Tanpa kartu kredit, tanpa komitmen.
                </flux:text>
            </div>
            <div class="reveal reveal-delay-2">
                @guest
                    <a href="{{ route('register') }}">
                        <flux:button variant="primary" class="btn-gradient-primary text-base py-2.5 px-8 rounded-xl font-semibold">
                            Mulai Sekarang — Gratis
                        </flux:button>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}">
                        <flux:button variant="primary" class="btn-gradient-primary text-base py-2.5 px-8 rounded-xl font-semibold">
                            Buka Dashboard
                        </flux:button>
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 bg-kb-surface-0 text-kb-text-muted text-center text-sm border-t border-kb-border-subtle">
        <p>&copy; {{ date('Y') }} Kawan Bisnis. AI Growth Team untuk UMKM Indonesia.</p>
    </footer>
</div>
