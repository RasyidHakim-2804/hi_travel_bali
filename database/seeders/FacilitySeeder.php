<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    private $facilities = [
        'Free Wi-Fi',
        'Air Conditioning',
        'Reclining Seats',
        'Charging Ports',
        'Entertainment System'
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $id = 1;

        foreach ($this->facilities as $facility ) {
            DB::table('facilities')->insert([
                'id'            => $id,
                'image'         => 'https://picsum.photos/200',
                'created_at'    => now(),
                'updated_at'    => null
            ]);

            $id++;
        }
    }
}
