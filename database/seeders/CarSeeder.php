<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'id'            => 1,
            'name'          => 'Standar (Hatchback)',
            'slug'          => Str::slug('Standar (Hatchback)'),
            'type'          => Product::CAR_TYPE,
            'max_person'    => 4,
            'idr_price'     => 50000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('products')->insert([
            'id'            => 2,
            'name'          => 'Mobil Keluarga (MPV)',
            'slug'          => Str::slug('Mobil Keluarga (MPV)'),
            'type'          => Product::CAR_TYPE,
            'max_person'    => 4,
            'idr_price'     => 70000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('products')->insert([
            'id'            => 3,
            'name'          => 'Mobil Keluarga Besar (MPV)',
            'slug'          => Str::slug('Mobil Keluarga Besar (MPV)'),
            'type'          => Product::CAR_TYPE,
            'max_person'    => 4,
            'idr_price'     => 55000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('products')->insert([
            'id'            => 4,
            'name'          => 'Eksekutif (Mini MPV)',
            'slug'          => Str::slug('Eksekutif (Mini MPV)'),
            'type'          => Product::CAR_TYPE,
            'max_person'    => 4,
            'idr_price'     => 35000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('products')->insert([
            'id'            => 5,
            'name'          => 'Mobil Keluarga Eksekutif (MPV)',
            'slug'          => Str::slug('Mobil Keluarga Eksekutif (MPV)'),
            'type'          => Product::CAR_TYPE,
            'max_person'    => 5,
            'idr_price'     => 90000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('products')->insert([
            'id'            => 6,
            'name'          => 'Minibus',
            'slug'          => Str::slug('Minibus'),
            'type'          => Product::CAR_TYPE,
            'max_person'    => 10,
            'idr_price'     => 60000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);

        DB::table('products')->insert([
            'id'            => 7,
            'name'          => 'Mewah (Mini MPV)',
            'slug'          => Str::slug('Mewah (Mini MPV)'),
            'type'          => Product::CAR_TYPE,
            'max_person'    => 4,
            'idr_price'     => 59000,
            'popular'       => rand(1, 10),
            'created_at'    => now(),
            'updated_at'    => null,
        ]);
    }
}
