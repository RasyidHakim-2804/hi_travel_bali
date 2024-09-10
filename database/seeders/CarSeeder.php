<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cars = [
            'Standar (Hatchback)',
            'Mobil Keluarga (MPV)',
            'Mobil Keluarga Besar (MPV)',
            'Eksekutif (Mini MPV)',
            'Mobil Keluarga Eksekutif (MPV)',
            'Minibus',
            'Mewah (Mini MPV)'
        ];

        $id = 1;

        foreach ($cars as $car) {
            DB::table('cars')->insert([
                'id'            => $id,
                'name'          => $car,
                'slug'          => Str::slug($car),
                'image'         => 'https://picsum.photos/300/300',
                'max_person'    => 4,
                'is_active'     => 1,
                'price'         => rand(50000, 100000),
                'created_at'    => now(),
                'updated_at'    => now(),
                'deleted_at'    => null,
            ]);

            $id++;
        }
    }
}
