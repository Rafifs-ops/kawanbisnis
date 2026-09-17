<?php

namespace Database\Seeders;

use App\Models\AgentKnowledge;
use Illuminate\Database\Seeder;
use Laravel\Ai\Embeddings;

class AgentKnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $knowledges = [
            // Analytics Agent Knowledge
            [
                'agent_type' => 'analytics',
                'title' => 'Anomali Penurunan Revenue F&B',
                'content' => 'Jika revenue bisnis F&B turun di atas 10% namun order tetap, periksa Average Order Value (AOV). Masalah biasanya ada pada pergeseran kombinasi produk (product mix) atau promo bundle yang terlalu dalam.',
            ],
            [
                'agent_type' => 'analytics',
                'title' => 'Pola Seasonal UMKM',
                'content' => 'UMKM Indonesia mengalami puncak penjualan pada Ramadan (+30-50%), Harbolnas, dan akhir bulan (gajian). Penurunan terjadi pada minggu pertama bulan. Analisis harus mempertimbangkan faktor seasonal.',
            ],
            [
                'agent_type' => 'analytics',
                'title' => 'Interpretasi AOV vs Revenue',
                'content' => 'AOV naik tapi revenue turun = volume order turun signifikan. AOV turun tapi revenue naik = strategi volume berhasil tapi margin tergerus. Keduanya naik = pertumbuhan sehat.',
            ],

            // Customer Agent Knowledge
            [
                'agent_type' => 'customer',
                'title' => 'Strategi Retention & Churn Rate',
                'content' => 'Penurunan customer repeat purchase sebesar 15% mengindikasikan penurunan kepuasan layanan atau hilangnya program loyalitas. Solusi: Buat penawaran retensi berbasis WhatsApp broadcast atau kupon kembali.',
            ],
            [
                'agent_type' => 'customer',
                'title' => 'Segmentasi Pelanggan UMKM',
                'content' => 'Pelanggan UMKM terbagi 3 segmen: Impulse Buyer (40%, beli karena promo), Loyal Regular (35%, repeat purchase), High Value (25%, besar tapi sedikit). Strategi berbeda untuk tiap segmen.',
            ],
            [
                'agent_type' => 'customer',
                'title' => 'Lifetime Value Pelanggan',
                'content' => 'CLV rata-rata UMKM Indonesia adalah 3-5x transaksi pertama. Jika CLV < 2x, ada masalah retensi serius. Fokus pada pengalaman pasca-pembelian dan follow-up.',
            ],

            // Marketing Agent Knowledge
            [
                'agent_type' => 'marketing',
                'title' => 'Efisiensi Kanal Instagram vs Marketplace',
                'content' => 'Apabila Return on Ad Spend (ROAS) Instagram Ads menurun < 2.0x, alokasikan budget pemasaran ke penawaran bundle pada marketplace lokal (Tokopedia/Shopee) untuk mengoptimalkan konversi cepat.',
            ],
            [
                'agent_type' => 'marketing',
                'title' => 'Budget Allocation Framework',
                'content' => 'Pembagian budget marketing ideal UMKM: 40% Akuisisi (Ads), 30% Retensi (WhatsApp/Email), 20% Content Creation, 10% Eksperimen. Jika salah satu > 50%, ada ketidakseimbangan.',
            ],
            [
                'agent_type' => 'marketing',
                'title' => 'Channel Performance Metrics',
                'content' => 'Instagram: ROAS > 3x = good, 2-3x = optimize, < 2x = reallocate. Tokopedia/Shopee: Conversion rate > 3% = good, 1-3% = optimize, < 1% = fix listing. WhatsApp: Response rate > 80% = good.',
            ],

            // Strategy Agent Knowledge
            [
                'agent_type' => 'strategy',
                'title' => 'Framework Scoring Prioritas UMKM',
                'content' => 'Penentuan prioritas aksi 7-30 hari harus dihitung dengan formula Score = Impact x Feasibility x Confidence. Maksimal 3 aksi teratas agar pengusaha UMKM tidak mengalami rekomendasi berlebih (overload).',
            ],
            [
                'agent_type' => 'strategy',
                'title' => 'Quick Win vs Long Term',
                'content' => 'Prioritas 7 hari = Quick Win (perubahan kecil dampak besar, contoh: optimasi deskripsi produk). Prioritas 30 hari = Strategic Move (butuh effort lebih, contoh: buat program loyalitas). Selalu mulai dari quick win.',
            ],
            [
                'agent_type' => 'strategy',
                'title' => 'Constraint-Aware Planning',
                'content' => 'Rencana aksi harus mempertimbangkan constraint: budget marketing terbatas (< Rp5jt/bulan = fokus organic), tim kecil (1-2 orang = automasi WhatsApp), tanpa dev team = gunakan tools no-code.',
            ],
        ];

        foreach ($knowledges as $item) {
            $embedding = null;

            try {
                $response = Embeddings::for([$item['content']])->generate();
                $embedding = $response->embeddings[0];
            } catch (\Throwable) {
                // Embedding generation may fail in testing/CI environments
            }

            AgentKnowledge::create([
                ...$item,
                'embedding' => $embedding,
            ]);
        }
    }
}
