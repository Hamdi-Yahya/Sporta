<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        
        $serverKey = config('midtrans.server_key');
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';
        
        // Verifikasi signature
        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        
        if ($calculatedSignature !== $signatureKey) {
            Log::error('Midtrans Webhook: Invalid Signature', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? '';
        
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();
        
        if (!$pembayaran) {
            Log::error('Midtrans Webhook: Order ID not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        $booking = $pembayaran->booking;

        // Idempotency: Jika status sudah terkonfirmasi, expired, atau ditolak, abaikan
        if (in_array($booking->status, [Booking::STATUS_TERKONFIRMASI, Booking::STATUS_EXPIRED, Booking::STATUS_DITOLAK, Booking::STATUS_SELESAI])) {
            return response()->json(['message' => 'Webhook already processed']);
        }

        // Update record pembayaran
        $pembayaran->update([
            'transaction_id'     => $payload['transaction_id'] ?? null,
            'payment_type'       => $payload['payment_type'] ?? null,
            'transaction_status' => $transactionStatus,
            'fraud_status'       => $fraudStatus,
            'paid_at'            => in_array($transactionStatus, ['settlement', 'capture']) ? now() : null,
        ]);

        // Tangani berdasarkan status transaksi
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                // Biarkan pending atau handle challenge
            } else if ($fraudStatus == 'accept') {
                $booking->update(['status' => Booking::STATUS_TERKONFIRMASI]);
            }
        } else if ($transactionStatus == 'settlement') {
            $booking->update(['status' => Booking::STATUS_TERKONFIRMASI]);
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {
            $booking->update(['status' => Booking::STATUS_DITOLAK]);
            $this->releaseSlot($booking);
        } else if ($transactionStatus == 'expire') {
            $booking->update(['status' => Booking::STATUS_EXPIRED]);
            $this->releaseSlot($booking);
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }

    private function releaseSlot(Booking $booking)
    {
        $slot = $booking->slot;
        if ($slot->tipe === 'biasa') {
            $slot->update(['status' => 'tersedia']);
        }
        // Jika open match, biarkan tersedia (karena status tidak berubah)
        // Jika ingin menambah/mengurangi kuota_terisi nanti bisa di sini, 
        // tapi saat ini Open Match kuota baru dikurangi jika sudah bayar?
        // Wait, logic BookingController store mengunci slot untuk biasa, tapi open_match tidak update status
        // Namun, pembayarannya belum beres. Kita harus hati-hati mengelola kuota.
    }
}
