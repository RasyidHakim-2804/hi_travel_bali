<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) { 
            DB::table('tour_translations')->insert([
                'tour_id'       => $i,
                'field'         => 'description',
                'language'      => 'id',
                'translation'   => '<h1>Tur 1 Hari di Nusa Penida Bagian Barat</h1><p>Tur ini menawarkan Anda kesempatan untuk menjelajahi pemandangan yang menakjubkan dan spot yang instagramable untuk diabadikan, terutama pemandangan pantai Kelingking yang indah.</p><h2>Highlight</h2><ul><li>Pemandangan pantai Kelingking</li><li>Angel’s Billabong</li><li>Broken Beach</li><li>Pantai Teluk Kristal</li></ul><h2>Rencana Perjalanan</h2><p>Tour ini akan dimulai dari Pelabuhan Sanur pada pukul 07:30 untuk mendapatkan tiket fast boat.</p><ul><li><strong>08:00</strong> - Kapal cepat berangkat ke Nusa Penida.</li><li><strong>09:00</strong> - Layanan pertemuan dengan sopir tim lokal kami dan memulai tur pulau hingga sore hari.</li><li><strong>16:00</strong> - Tiba kembali di pelabuhan Nusa Penida dan menyelesaikan tur sehari.</li><li><strong>16:30</strong> - Kapal cepat kembali ke Sanur.</li><li><strong>17:15</strong> - Tiba kembali di Sanur.</li></ul><h2>Sudah Termasuk</h2><ul><li>Tiket fast boat pulang pergi ke Nusa Penida.</li><li>Mobil pribadi untuk tur sehari di Nusa Penida.</li><li>Pengemudi sebagai pemandu dan fotografer.</li><li>Makan siang di restoran lokal.</li><li>Air mineral.</li><li>Tiket masuk ke semua objek wisata tersebut.</li><li>Biaya masuk di pelabuhan Nusa Penida.</li></ul><h2>Tidak Termasuk</h2><ul><li>Transportasi dari hotel di Bali ke pelabuhan Sanur. Bisa request dengan harga tambahan.</li><li>Tiket ke tempat selfie milik pribadi di kawasan tebing Kelingking dan Paluang; semua tiket tersebut dapat Anda beli di tempat jika Anda ingin memilikinya.</li></ul>',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::table('tour_translations')->insert([
                'tour_id'       => $i,
                'field'         => 'description',
                'language'      => 'en',
                'translation'   => '<h1>1 Day Tour in West Nusa Penida</h1><p>This tour offers you the opportunity to explore stunning views and instagrammable spots, especially the beautiful Kelingking Beach view.</p><h2>Highlights</h2><ul><li>Kelingking Beach view</li><li>Angel’s Billabong</li><li>Broken Beach</li><li>Crystal Bay Beach</li></ul><h2>Itinerary</h2><p>The tour will start from Sanur Harbor at 07:30 to get the fast boat ticket.</p><ul><li><strong>08:00</strong> - The fast boat departs for Nusa Penida.</li><li><strong>09:00</strong> - Meet with our local team driver and start the island tour until the afternoon.</li><li><strong>16:00</strong> - Arrive back at Nusa Penida harbor and complete the day tour.</li><li><strong>16:30</strong> - The fast boat returns to Sanur.</li><li><strong>17:15</strong> - Arrive back at Sanur.</li></ul><h2>Included</h2><ul><li>Return fast boat ticket to Nusa Penida.</li><li>Private car for a day tour in Nusa Penida.</li><li>Driver as guide and photographer.</li><li>Lunch at a local restaurant.</li><li>Mineral water.</li><li>Entrance tickets to all the tourist attractions.</li><li>Entrance fee at Nusa Penida harbor.</li></ul><h2>Not Included</h2><ul><li>Transportation from hotel in Bali to Sanur harbor. Can request with additional cost.</li><li>Ticket to private-owned selfie spots in the Kelingking and Paluang cliff areas; all tickets can be purchased on-site if you wish to enter.</li></ul>',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
