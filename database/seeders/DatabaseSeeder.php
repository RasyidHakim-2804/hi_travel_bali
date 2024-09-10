<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        (new LanguageSeeder)->run();
        (new AdminSeeder)->run();

        (new LocationSeeder)->run();
        (new FacilitySeeder)->run();
        (new FacilityTranslationSeeder)->run();

        // product
        // (new TransportSeeder)->run();
        // (new TourSeeder)->run();

        // (new ProductTranslationSeeder)->run();
        // (new PivotProductFacilitySeeder)->run();
        // (new PivotProductLocationSeeder)->run();

        //article
    }
}
