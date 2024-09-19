<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_variant_variant_translations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tour_variant_id')->nullable();
            $table->foreign('tour_variant_id')->references('id')->on('tour_variants')->cascadeOnDelete();

            $table->string('language', 20);
            $table->string('field');
            $table->text('translation');
            $table->timestamps();

            $table->foreign('language')->references('code')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_variant_translations');
    }
};
