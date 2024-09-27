<?php

namespace Database\Seeders;

use App\Models\TourVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TourVariantTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $variants = TourVariant::get(['id', 'name'])->toArray();

        foreach ($variants as $variant) {

            if ($variant['name'] == 'Dewasa') {
                $description['id']  = 'Peserta tur dari umur 11 tahun keatas.';
                $description['en']  = 'Tour participants from age 11 and over.';
            } else {
                $description['id']  = 'Peserta tur daari umur 1-10 tahun';
                $description['en']  = 'Tour participants from 1-10 years old';
            }

            DB::table('tour_variant_translations')->insert([
                'tour_variant_id'       => $variant['id'],
                'field'         => 'description',
                'language'      => 'id',
                'translation'   => $description['id'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::table('tour_variant_translations')->insert([
                'tour_variant_id'       => $variant['id'],
                'field'         => 'description',
                'language'      => 'en',
                'translation'   => $description['en'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
