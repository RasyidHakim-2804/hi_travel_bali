<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facility_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('facilities', 'id');
            $table->string('lang', 20);
            $table->string('title');
            $table->timestamps();
            
            $table->foreign('lang')->references('code')->on('languages');
            $table->unique(['facility_id', 'lang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_translations');
    }
};
