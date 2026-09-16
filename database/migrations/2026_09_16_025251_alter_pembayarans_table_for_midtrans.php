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
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn(['bukti_transfer', 'waktu_upload', 'catatan_reject']);

            $table->string('order_id')->unique()->after('booking_id');
            $table->string('transaction_id')->nullable()->after('order_id');
            $table->string('payment_type')->nullable()->after('transaction_id');
            $table->integer('gross_amount')->default(0)->after('payment_type');
            $table->string('transaction_status')->default('pending')->after('gross_amount');
            $table->string('fraud_status')->nullable()->after('transaction_status');
            $table->string('snap_token')->nullable()->after('fraud_status');
            $table->timestamp('paid_at')->nullable()->after('snap_token');
            $table->timestamp('expired_at')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('bukti_transfer')->nullable();
            $table->timestamp('waktu_upload')->nullable();
            $table->text('catatan_reject')->nullable();

            $table->dropColumn([
                'order_id', 'transaction_id', 'payment_type', 'gross_amount', 
                'transaction_status', 'fraud_status', 'snap_token', 'paid_at', 'expired_at'
            ]);
        });
    }
};
