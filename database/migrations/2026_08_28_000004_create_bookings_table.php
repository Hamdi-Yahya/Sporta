<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_id')->constrained('slots')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipe_booking', ['biasa', 'open_match'])->default('biasa');
            $table->unsignedSmallInteger('jumlah_kursi')->default(1);
            $table->unsignedInteger('total_harga');
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'terkonfirmasi',
                'ditolak',
                'expired',
                'selesai',
            ])->default('menunggu_pembayaran');
            $table->timestamp('waktu_booking')->nullable();
            $table->timestamp('batas_waktu_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
