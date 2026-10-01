<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WhatsAppOtpSender
{
    public function send(string $phone, string $otp): void
    {
        $driver = config('services.whatsapp_otp.driver', 'log');

        if ($driver === 'log') {
            if (app()->environment('production')) {
                throw new RuntimeException('The WhatsApp OTP provider is not configured.');
            }

            Log::info('WhatsApp registration OTP', ['phone' => $phone, 'otp' => $otp]);

            return;
        }

        if ($driver !== 'meta') {
            throw new RuntimeException('Unsupported WhatsApp OTP driver.');
        }

        $token = config('services.whatsapp_otp.access_token');
        $phoneNumberId = config('services.whatsapp_otp.phone_number_id');

        if (! $token || ! $phoneNumberId) {
            throw new RuntimeException('The Meta WhatsApp credentials are incomplete.');
        }

        $version = config('services.whatsapp_otp.graph_version', 'v25.0');
        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $response = Http::asJson()
            ->withToken($token)
            ->withOptions(['connect_timeout' => 5])
            ->timeout(15)
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => ltrim($phone, '+'),
                'type' => 'template',
                'template' => [
                    'name' => config('services.whatsapp_otp.template_name', 'registration_otp'),
                    'language' => [
                        'code' => config('services.whatsapp_otp.template_language', 'en_US'),
                    ],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => [['type' => 'text', 'text' => $otp]],
                        ],
                        [
                            'type' => 'button',
                            'sub_type' => 'url',
                            'index' => '0',
                            'parameters' => [['type' => 'text', 'text' => $otp]],
                        ],
                    ],
                ],
            ]);

        if ($response->failed()) {
            Log::error('Meta rejected a WhatsApp OTP message.', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new RuntimeException('WhatsApp could not deliver the verification code.');
        }
    }
}
