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
                'is_active'     => 1,
                'is_varied'     => 1,
                'created_at'    => now(),
                'updated_at'    => null,
            ]);
        }
    }
}
