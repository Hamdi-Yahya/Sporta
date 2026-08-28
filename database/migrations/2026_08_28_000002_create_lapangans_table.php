<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lapangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cabor_id')->constrained('cabang_olahragas')->cascadeOnDelete();
            $table->string('nama');
            $table->string('lokasi');
            $table->text('deskripsi')->nullable();
            $table->text('fasilitas')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('alasan_reject')->nullable();
            $table->decimal('rating_rata2', 3, 2)->default(0);
            $table->unsignedInteger('jumlah_ulasan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapangans');
    }
};
