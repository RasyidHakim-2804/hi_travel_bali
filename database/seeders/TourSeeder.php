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
        DB::table('products')->insert([
            'id'            => 8,
            'name'          => 'West Nusa Penida Tour',
            'slug'          => Str::slug('West Nusa Penida Tour'),
            'type'          => Product::TOUR_TYPE,
            'max_person'    => 4,
            'idr_price'     => 400000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('products')->insert([
            'id'            => 9,
            'name'          => 'Nusa Penida East Tour',
            'slug'          => Str::slug('Nusa Penida East Tour'),
            'type'          => Product::TOUR_TYPE,
            'max_person'    => 4,
            'idr_price'     => 425000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('products')->insert([
            'id'            => 10,
            'name'          => 'Bedugul & Natural Treasures',
            'slug'          => Str::slug('Bedugul & Natural Treasures'),
            'type'          => Product::TOUR_TYPE,
            'max_person'    => 4,
            'idr_price'     => 425000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('products')->insert([
            'id'            => 11,
            'name'          => 'Lempuyang, Gate of Heaven & Snorkeling',
            'slug'          => Str::slug('Lempuyang, Gate of Heaven & Snorkeling'),
            'type'          => Product::TOUR_TYPE,
            'max_person'    => 4,
            'idr_price'     => 670000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);
    }
}
