<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'West Nusa Penida Tour',
            'Nusa Penida East Tour',
            'Bedugul & Natural Treasures',
            'Lempuyang, Gate of Heaven & Snorkeling'
        ];

        foreach ($names as $tour_id => $name) {
            
            $tour_id = $tour_id +1;

            DB::table('tours')->insert([
                'id'            => $tour_id,
                'name'          => $name,
                'slug'          => Str::slug($name),
                'max_person'    => 4,
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => null,
            ]);

            DB::table('tour_variants')->insert([
                'id'            => $tour_id,
                'tour_id'       => $tour_id,
                'is_default'    => 1,
                'price'         => rand(300000, 400000),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
