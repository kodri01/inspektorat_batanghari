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
        Schema::create('rekomendasies', function (Blueprint $table) {
            $table->id();
            $table->integer('wilayah_id');
            $table->integer('temuan_id');
            $table->integer('obrik_id');
            $table->integer('lhp_id');
            $table->string('rekomendasi');
            $table->bigInteger('nilai_rekomendasi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasies');
    }
};
