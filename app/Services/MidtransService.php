<?php

namespace App\Services;

use App\Models\Booking;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $serverKey;
    protected $isProduction;
    protected $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        $this->baseUrl = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1' 
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    /**
     * Memanggil API Snap Midtrans untuk mendapatkan token
     *
     * @param Booking $booking
     * @param string $orderId
     * @return string|null Snap Token
     */
    public function getSnapToken(Booking $booking, string $orderId)
    {
        $itemDetails = [];

        // Detail lapangan
        $itemDetails[] = [
            'id'       => 'SLOT-' . $booking->slot->id,
            'price'    => $booking->total_harga,
            'quantity' => 1,
            'name'     => substr('Booking ' . $booking->slot->lapangan->nama, 0, 50),
        ];

        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $booking->total_harga,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email'      => $booking->user->email,
                'phone'      => $booking->user->no_hp ?? '',
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/transactions', $payload);

            if ($response->successful()) {
                return $response->json('token');
            }

            Log::error('Midtrans Snap Error: ' . $response->body());
            return null;
        } catch (Exception $e) {
            Log::error('Midtrans Snap Exception: ' . $e->getMessage());
            return null;
        }
    }
}
