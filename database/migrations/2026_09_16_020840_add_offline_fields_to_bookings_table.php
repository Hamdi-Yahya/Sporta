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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            $table->enum('sumber_booking', ['online', 'offline'])->default('online')->after('user_id');
            $table->string('nama_pemesan_offline')->nullable()->after('sumber_booking');
            $table->string('no_hp_pemesan_offline')->nullable()->after('nama_pemesan_offline');
            $table->text('catatan_offline')->nullable()->after('no_hp_pemesan_offline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->dropColumn(['sumber_booking', 'nama_pemesan_offline', 'no_hp_pemesan_offline', 'catatan_offline']);
        });
    }
};
