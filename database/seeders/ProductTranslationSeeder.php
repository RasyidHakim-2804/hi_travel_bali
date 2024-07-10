<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTranslationSeeder extends Seeder
{
    private $tourIds = [8,9,10,11];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->tourIds as $product_id) {
            DB::table('product_translations')->insert([
                'product_id'    => $product_id,
                'lang'          => 'id',
                'description'   => fake()->paragraph(),
            ]);

            DB::table('product_translations')->insert([
                'product_id'    => $product_id,
                'lang'          => 'en',
                'description'   => fake()->paragraph(),
            ]);
        }
    }
}
