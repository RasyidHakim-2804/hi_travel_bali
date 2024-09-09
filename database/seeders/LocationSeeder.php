<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    private $locations = [
        'Nusa Penida',
        'Gilimanuk',
        'Bedugul',
        'Kuta',
        'jimbaran'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->locations as $location) {
            DB::table('locations')->insert([
                'name'          => $location,
                'created_at'    => now(),
                'updated_at'    => null,
            ]);
        }
    }
}
