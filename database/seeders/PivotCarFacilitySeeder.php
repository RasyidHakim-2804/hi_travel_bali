<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PivotCarFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($car_id=1; $car_id <=7 ; $car_id++) { 
            for ($facility_id=1; $facility_id < 5; $facility_id++) { 
                
                DB::table('pivot_car_facility')->insert([
                    'car_id'        => $car_id,
                    'facility_id'   => $facility_id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
