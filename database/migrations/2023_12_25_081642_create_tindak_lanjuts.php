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
        Schema::create('tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->integer('wilayah_id');
            $table->integer('obrik_id');
            $table->integer('temuan_id');
            $table->integer('lhp_id');
            $table->integer('rekomendasi_id');
            $table->string('uraian');
            $table->string('status_tl');
            $table->bigInteger('nilai_selesai')->nullable();
            $table->bigInteger('nilai_dalam_proses')->nullable();
            $table->bigInteger('nilai_sisa')->nullable();
            $table->string('saran');
            $table->string('file');
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjuts');
    }
};
