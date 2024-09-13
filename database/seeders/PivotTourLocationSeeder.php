<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PivotTourLocationSeeder extends Seeder
{
    private $tourIds = [8,9,10,11];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i <= 4 ; $i++) { 
            for ($i = 1; $i <= rand(1,5) ; $i++) { 
                DB::table('pivot_tour_location')->insert([
                    'tour_id'       => $i,
                    'location_id'   => $i,
                    'created_at'    => now(),
                    'updated_at'    => null,
                ]);
            }
        }
    }
}
