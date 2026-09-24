<div>
    {{-- Hero — Light blue brand --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-[#0A1116] via-[#0A2A9E] to-[#081E8E]">
        <div class="absolute inset-0 opacity-40" style="background: radial-gradient(ellipse at 50% 0%, rgba(255,255,255,0.7) 0%, transparent 55%);"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-24">
            <div class="text-center">
                <div class="reveal mb-2">
                    <x-app-logo-icon class="mx-auto h-30 w-auto" />
                </div>

                <flux:heading level="1" class="font-display text-4xl lg:text-6xl font-bold mb-6 leading-[1.1] text-white tracking-tight">
                    <span class="word-reveal" style="animation-delay: 0.1s">Tim</span>
                    <span class="word-reveal" style="animation-delay: 0.15s">AI</span>
                    <span class="word-reveal" style="animation-delay: 0.2s">untuk</span>
                    <br class="hidden sm:block" />
                    <span class="word-reveal" style="animation-delay: 0.3s;">Pertumbuhan</span>
                    <span class="word-reveal" style="animation-delay: 0.35s">UMKM</span>
                </flux:heading>

                <div class="reveal reveal-delay-2">
                    <flux:text class="text-lg text-white mb-10 max-w-2xl mx-auto leading-relaxed">
                        4 agen AI menganalisis bisnis Anda secara menyeluruh — dari data, pelanggan, marketing, hingga strategi — lalu menghasilkan diagnosis dan rencana aksi prioritas.
                    </flux:text>
                </div>

                {{-- Pipeline Visualization --}}
                <div class="reveal reveal-delay-3 mb-10">
                    <div class="inline-flex flex-wrap items-center justify-center gap-2 sm:gap-3 px-6 py-4 rounded-2xl glass">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-500/15 border border-blue-500/25 flex items-center justify-center">
                                <flux:icon name="chart-bar" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-text-muted font-medium">Analytics</span>
                        </div>

                        <div class="w-6 sm:w-10 h-px bg-gradient-to-r from-blue-500/40 to-emerald-500/40 hidden sm:block"></div>

                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center">
                                <flux:icon name="users" class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-text-muted font-medium">Customer</span>
                        </div>

                        <div class="w-6 sm:w-10 h-px bg-gradient-to-r from-emerald-500/40 to-amber-500/40 hidden sm:block"></div>

                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center">
                                <flux:icon name="megaphone" class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-text-muted font-medium">Marketing</span>
                        </div>

                        <div class="w-6 sm:w-10 h-px bg-gradient-to-r from-amber-500/40 to-rose-500/40 hidden sm:block"></div>

                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-rose-500/15 border border-rose-500/25 flex items-center justify-center">
                                <flux:icon name="light-bulb" class="w-5 h-5 sm:w-6 sm:h-6 text-rose-600" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-text-muted font-medium">Strategy</span>
                        </div>

                        <div class="w-6 sm:w-10 h-px bg-gradient-to-r from-rose-500/40 to-kb-blue-light/60 hidden sm:block"></div>

                        <div class="flex flex-col items-center gap-1">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-kb-blue-electric to-kb-blue-light flex items-center justify-center shadow-brand">
                                <flux:icon name="sparkles" class="w-5 h-5 sm:w-6 sm:h-6 text-white" />
                            </div>
                            <span class="text-[10px] sm:text-xs text-kb-blue-electric font-semibold">Diagnosis</span>
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
                            <flux:button variant="primary" class="btn-gradient-primary text-base py-2.5 px-6 rounded-xl font-semibold">
                                Masuk
                            </flux:button>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Problem Framework — Dark blue --}}
    <section class="py-20 bg-gradient-to-b from-[#081E8E] via-[#0A2A9E] to-[#0A1116]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="reveal">
                    <h1 class="font-display text-3xl lg:text-4xl font-bold mb-4 text-white leading-tight">
                        Masalah yang Dihadapi Owner UMKM Ketika Menjalakan Usaha Dengan Sedikit Orang
                    </h1>
                </div>
                <div class="reveal reveal-delay-1">
                    <p class="text-[#9DE6FA]/85 max-w-2xl mx-auto">
                        6 hambatan utama yang sering dihadapi saat menjalankan usaha hanya dengan sedikit orang.
                    </p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    [
                        'icon' => 'academic-cap',
                        'title' => 'Keterbatasan keahlian karena skill pas-pasan',
                        'desc' => 'Mengurus semuanya sendirian dengan kemampuan yang terbatas di banyak bidang.',
                    ],
                    [
                        'icon' => 'building-storefront',
                        'title' => 'Cuma bisa nguasai 1 bidang',
                        'desc' => 'Hanya fokus pada satu bidang, sementara aspek lain usaha kurang dikuasai.',
                    ],
                    [
                        'icon' => 'eye',
                        'title' => 'Sudut Pandang Terbatas',
                        'desc' => 'Keputusan diambil tanpa perspektif yang luas dan objektif.',
                    ],
                    [
                        'icon' => 'fire',
                        'title' => 'Beban Kerja Belebih',
                        'desc' => 'Terlalu banyak tugas menumpuk sehingga energi dan waktu terkuras habis.',
                    ],
                    [
                        'icon' => 'exclamation-triangle',
                        'title' => 'Analisis berantakan pada semua aspek bisnis',
                        'desc' => 'Evaluasi bisnis tidak terstruktur dan sulit diandalkan untuk ambil keputusan.',
                    ],
                    [
                        'icon' => 'calendar-days',
                        'title' => 'Sulit Merencanakan Untuk Mengembangkan Bisnis nya',
                        'desc' => 'Tidak punya rencana jelas untuk menumbuhkan usaha ke tahap berikutnya.',
                    ],
                ] as $i => $problem)
                    <div class="reveal reveal-delay-{{ ($i % 6) + 1 }}">
                        <div class="h-full p-6 rounded-2xl border border-white/15 bg-white/10 backdrop-blur-sm card-glow">
                            <div class="w-12 h-12 rounded-xl bg-[#9DE6FA]/15 border border-[#9DE6FA]/30 flex items-center justify-center mb-4">
                                <flux:icon name="{{ $problem['icon'] }}" class="w-6 h-6 text-[#9DE6FA]" />
                            </div>
                            <flux:heading level="3" class="font-display text-lg font-semibold mb-2 text-white">
                                {{ $problem['title'] }}
                            </flux:heading>
                            <flux:text class="text-[#9DE6FA]/75 text-sm leading-relaxed">
                                {{ $problem['desc'] }}
                            </flux:text>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- AI Team Preview — Soft light blue --}}
    <section class="py-20 bg-gradient-to-b from-[#e8f7fe] via-[#f5fbff] to-white section-glow">
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
                        ['name' => 'Analytics Agent', 'icon' => 'chart-bar', 'desc' => 'Menganalisis angka, tren, dan anomali dari data bisnis.', 'color' => 'blue', 'gradient' => 'from-blue-500/20 to-blue-600/5', 'border' => 'border-blue-500/20', 'iconBg' => 'bg-blue-500/15', 'iconColor' => 'text-blue-600', 'tagColor' => 'bg-blue-500/10 text-blue-700 border-blue-500/20'],
                        ['name' => 'Customer Agent', 'icon' => 'users', 'desc' => 'Memahami perilaku pelanggan, retensi, dan churn.', 'color' => 'emerald', 'gradient' => 'from-emerald-500/20 to-emerald-600/5', 'border' => 'border-emerald-500/20', 'iconBg' => 'bg-emerald-500/15', 'iconColor' => 'text-emerald-600', 'tagColor' => 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20'],
                        ['name' => 'Marketing Agent', 'icon' => 'megaphone', 'desc' => 'Mengevaluasi efektivitas channel pemasaran.', 'color' => 'amber', 'gradient' => 'from-amber-500/20 to-amber-600/5', 'border' => 'border-amber-500/20', 'iconBg' => 'bg-amber-500/15', 'iconColor' => 'text-amber-600', 'tagColor' => 'bg-amber-500/10 text-amber-700 border-amber-500/20'],
                        ['name' => 'Strategy Agent', 'icon' => 'light-bulb', 'desc' => 'Mensintesis semua temuan jadi rencana aksi prioritas.', 'color' => 'rose', 'gradient' => 'from-rose-500/20 to-rose-600/5', 'border' => 'border-rose-500/20', 'iconBg' => 'bg-rose-500/15', 'iconColor' => 'text-rose-600', 'tagColor' => 'bg-rose-500/10 text-rose-700 border-rose-500/20'],
                    ];
                @endphp

                @foreach ($agents as $i => $agent)
                    <div class="reveal reveal-delay-{{ $i + 1 }}">
                        <div class="h-full p-6 rounded-2xl border {{ $agent['border'] }} bg-white bg-gradient-to-br {{ $agent['gradient'] }} card-glow shadow-sm">
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

    {{-- How It Works — Mid light blue --}}
    <section class="py-20 bg-gradient-to-b from-[#6fc6fa] via-[#8ed4fb] to-[#b8e9fc]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="reveal">
                    <h1 class="font-display text-3xl font-bold mb-4 text-kb-text-primary">Cara Kerja</h1>
                </div>
                <div class="reveal reveal-delay-1">
                    <p class="text-kb-text-secondary max-w-2xl mx-auto">
                        Siklus pertumbuhan yang berkelanjutan untuk bisnis Anda.
                    </p>
                </div>
            </div>
            <div class="relative">
                {{-- Roadmap spine (desktop) --}}
                <div class="hidden md:block absolute top-7 left-[10%] right-[10%] h-0.5 bg-gradient-to-r from-kb-blue-electric/15 via-kb-blue-electric/45 to-kb-blue-electric/15 rounded-full"></div>

                <div class="relative flex flex-col md:flex-row md:items-start md:justify-center gap-4 md:gap-1">
                    @foreach([
                        ['step' => '01', 'title' => 'Isi Profil Bisnis', 'desc' => 'Profil bisnis, produk, channel'],
                        ['step' => '02', 'title' => 'Upload Data Penjualan', 'desc' => 'Data 3 bulan terakhir'],
                        ['step' => '03', 'title' => 'AI Menganalisis', 'desc' => '4 agen bekerja menganalisis'],
                        ['step' => '04', 'title' => 'Dapat Diagnosis', 'desc' => 'Masalah, akar masalah, peluang'],
                        ['step' => '05', 'title' => 'Action Plan Prioritas', 'desc' => 'Top 3 aksi 7-30 hari'],
                        ['step' => '06', 'title' => 'Tandai Selesai & Evaluasi', 'desc' => 'Hasil → knowledge AI → siklus berikutnya'],
                    ] as $i => $item)
                        <div class="flex flex-col items-center text-center md:flex-1 md:max-w-[10rem] reveal reveal-delay-{{ ($i % 6) + 1 }}">
                            <div class="relative z-10 w-14 h-14 rounded-full bg-white border-2 border-kb-blue-electric/30 flex items-center justify-center mb-4 shadow-sm">
                                <span class="kpi-number text-lg text-kb-blue-electric">{{ $item['step'] }}</span>
                            </div>
                            <div class="w-full p-4 rounded-xl bg-white/90 border border-kb-blue-electric/10 shadow-sm">
                                <flux:heading level="3" class="font-display text-sm font-semibold mb-1 text-kb-text-primary">{{ $item['title'] }}</flux:heading>
                                <flux:text class="text-kb-text-secondary text-xs leading-relaxed">{{ $item['desc'] }}</flux:text>
                            </div>
                        </div>
                        @unless ($loop->last)
                            {{-- Arrow to next step: right on desktop, down on mobile --}}
                            <div class="flex items-center justify-center shrink-0 md:self-start md:mt-6 md:w-8" aria-hidden="true">
                                <flux:icon name="arrow-right" class="w-5 h-5 md:hidden text-kb-blue-electric/55 max-md:rotate-90" />
                            </div>
                        @endunless
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Banner — Deep dark blue --}}
    <section class="py-20 relative overflow-hidden bg-gradient-to-br from-[#0A1116] via-[#081E8E] to-[#0F3FB0]">
        <div class="absolute inset-0 opacity-30" style="background: radial-gradient(ellipse at 50% 100%, rgba(111,198,250,0.45) 0%, transparent 55%);"></div>

        <div class="relative max-w-4xl mx-auto px-6 text-center">
            <div class="reveal">
                <h1 class="font-display text-3xl font-bold mb-4 text-white">
                    Siap Memulai Pertumbuhan?
                </h1>
            </div>
            <div class="reveal reveal-delay-1">
                <flux:text class="text-[#9DE6FA]/90 mb-8 text-lg">
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
    <footer class="py-8 bg-[#0A1116] text-[#9DE6FA]/70 text-center text-sm border-t border-white/10">
        <p>&copy; {{ date('Y') }} Kawan Bisnis. AI Growth Team untuk UMKM Indonesia.</p>
    </footer>
</div>
