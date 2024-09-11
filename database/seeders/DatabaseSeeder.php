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
        // (new AdminSeeder)->run();

        (new LocationSeeder)->run();
        (new FacilitySeeder)->run();
        (new FacilityTranslationSeeder)->run();

        // Car
        (new CarSeeder)->run();
        (new PivotCarFacilitySeeder)->run();
        
        // (new TourSeeder)->run();
        // (new ProductTranslationSeeder)->run();
        // (new PivotProductLocationSeeder)->run();

        //article
    }
}
