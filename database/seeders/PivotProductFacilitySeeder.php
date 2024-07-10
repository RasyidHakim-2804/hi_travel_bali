<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PivotProductFacilitySeeder extends Seeder
{
    private $carIds = [1,2,3,4,5,6,7];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->carIds as $product_id) {
            for ($i = 1; $i <= rand(1,5) ; $i++) { 
                DB::table('pivot_product_facility')->insert([
                    'product_id'    => $product_id,
                    'facility_id'   => $i,
                    'created_at'    => now(),
                    'updated_at'    => null,
                ]);
            }
        }
    }
}
