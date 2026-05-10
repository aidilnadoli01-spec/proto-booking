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
        Schema::create('antrean', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_dokter_id')->constrained('jadwal_dokter')->cascadeOnDelete();
            $table->date('tanggal_periksa');
            $table->unsignedInteger('nomor_antrean');
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'batal'])->default('menunggu');
            $table->timestamps();

            $table->unique(['jadwal_dokter_id', 'tanggal_periksa', 'nomor_antrean'], 'antrean_nomor_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrean');
    }
};
