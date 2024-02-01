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
        Schema::create('penanggung_jawabs', function (Blueprint $table) {
            $table->id();
            $table->integer('temuan_id');
            $table->integer('obrik_id')->nullable();
            $table->integer('nilai_obrik')->nullable();
            $table->string('name')->nullable();
            $table->string('nip')->nullable();
            $table->integer('nilai')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penanggung_jawabs');
    }
};
