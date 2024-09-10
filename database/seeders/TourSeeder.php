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
        DB::table('tours')->insert([
            'id'            => 1,
            'name'          => 'West Nusa Penida Tour',
            'slug'          => Str::slug('West Nusa Penida Tour'),
            'max_person'    => 4,
            'price'     => 400000,
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('tours')->insert([
            'id'            => 2,
            'name'          => 'Nusa Penida East Tour',
            'slug'          => Str::slug('Nusa Penida East Tour'),
            'max_person'    => 4,
            'price'     => 425000,
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('tours')->insert([
            'id'            => 3,
            'name'          => 'Bedugul & Natural Treasures',
            'slug'          => Str::slug('Bedugul & Natural Treasures'), 
            'max_person'    => 4,
            'price'     => 425000,
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('tours')->insert([
            'id'            => 4,
            'name'          => 'Lempuyang, Gate of Heaven & Snorkeling',
            'slug'          => Str::slug('Lempuyang, Gate of Heaven & Snorkeling'),    
            'max_person'    => 4,
            'price'     => 670000,
            'created_at'    => now(),
            'updated_at'    => null,
        ]);
    }
}
