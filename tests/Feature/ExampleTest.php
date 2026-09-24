<?php

test('returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('landing shows new umkm problem section content', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Masalah yang Dihadapi Owner UMKM Ketika Menjalakan Usaha Dengan Sedikit Orang', false)
        ->assertSee('Keterbatasan keahlian karena skill pas-pasan')
        ->assertSee('Cuma bisa nguasai 1 bidang')
        ->assertSee('Sudut Pandang Terbatas')
        ->assertSee('Beban Kerja Belebih')
        ->assertSee('Analisis berantakan pada semua aspek bisnis')
        ->assertSee('Sulit Merencanakan Untuk Mengembangkan Bisnis nya');
});

test('landing sections use varied blue backgrounds', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)
        ->toContain('from-[#6fc6fa]')      // hero light blue
        ->toContain('from-[#081E8E]')      // problem dark blue
        ->toContain('from-[#e8f7fe]')      // AI team soft light
        ->toContain('from-[#0A1116]')      // CTA deep dark
        ->toContain('bg-[#0A1116]');       // footer dark
});

test('cara kerja section is a roadmap with arrows between steps', function () {
    $html = $this->get(route('home'))->assertOk()->getContent();

    expect($html)
        ->toContain('M13.5 4.5 21 12')                 // arrow-right SVG path (5 arrows between steps)
        ->toContain('hidden md:block absolute top-7')   // roadmap spine
        ->toContain('Isi Profil Bisnis')
        ->toContain('Tandai Selesai &amp; Evaluasi')
        ->toContain('max-md:rotate-90');                // mobile: arrow points down
});
