<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class SmsHelper
{
    /**
     * Send SMS via WhySMS API
     */
    public static function send(
        string $recipient,
        string $message,
        string $senderId = 'YourName',
        string $type = 'plain'
    ): array {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.whysms.token'),
                'Accept'        => 'application/json',
            ])->post('https://bulk.whysms.com/api/v3/sms/send', [
                'recipient' => $recipient,
                'sender_id' => config('services.whysms.sender_id'),
                'type'      => $type,
                'message'   => $message,
            ]);

            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'data'    => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status'  => 500,
                'error'   => $e->getMessage(),
            ];
        }
    }
}
