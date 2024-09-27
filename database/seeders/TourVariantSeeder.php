<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tour_ids = Tour::pluck('id')->toArray();

        foreach ($tour_ids as $tour_id) {
            DB::table('tour_variants')->insert([
                'tour_id'       => $tour_id,
                'name'          => 'Dewasa',
                'max_person'    => 4,
                'is_default'    => 0,
                'price'         => rand(300000, 400000),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::table('tour_variants')->insert([
                'tour_id'       => $tour_id,
                'name'          => 'Anak',
                'max_person'    => 4,
                'is_default'    => 0,
                'price'         => rand(300000, 400000),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
