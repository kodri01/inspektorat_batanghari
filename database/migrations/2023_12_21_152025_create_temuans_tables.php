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
        Schema::create('temuans', function (Blueprint $table) {
            $table->id();
            $table->integer('wilayah_id');
            $table->integer('obrik_id');
            $table->integer('lhp_id');
            $table->string('jns_pemeriksaan');
            $table->string('ringkasan');
            $table->integer('nilai_temuan');
            $table->string('jns_temuan');
            $table->string('rekomendasi');
            $table->integer('nilai_rekomendasi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temuans');
    }
};
