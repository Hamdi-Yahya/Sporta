<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangans')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('tipe', ['biasa', 'open_match'])->default('biasa');
            $table->unsignedInteger('harga');
            $table->unsignedSmallInteger('kuota_total')->nullable();
            $table->unsignedSmallInteger('kuota_terisi')->default(0);
            $table->enum('status', ['tersedia', 'dibooking', 'penuh', 'selesai'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
