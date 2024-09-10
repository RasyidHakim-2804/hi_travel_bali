<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilityTranslationSeeder extends Seeder
{
    private $facilities = [
        [
            'id' => 'WiFi Gratis', 
            'en' => 'Free Wi-Fi'
        ],
        [
            'id' => 'AC', 
            'en' => 'Air Conditioning'
        ],
        [
            'id' => 'Kursi Bisa Direbahkan', 
            'en' => 'Reclining Seats'
        ],
        [
            'id' => 'Port Pengisian Daya', 
            'en' => 'Charging Ports'
        ],
        [
            'id' => 'Sistem Hiburan', 
            'en' => 'Entertainment System'
        ]
    ];
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $i = 1;

        foreach ($this->facilities as $key) {
            DB::table('facility_translations')->insert([
                'facility_id'   => $i,
                'language'      => 'id',
                'field'         => 'description',
                'translation'   => $key['id'],
                'created_at'    => now(),
                'updated_at'    => null
            ]);

            DB::table('facility_translations')->insert([
                'facility_id'   => $i,
                'language'      => 'en',
                'field'         => 'description',
                'translation'   => $key['en'],
                'created_at'    => now(),
                'updated_at'    => null
            ]);

            $i++;
        }
    }
}
