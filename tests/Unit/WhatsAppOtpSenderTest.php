<?php

namespace Tests\Unit;

use App\Services\WhatsAppOtpSender;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppOtpSenderTest extends TestCase
{
    public function test_it_sends_an_authentication_template_through_meta(): void
    {
        config([
            'services.whatsapp_otp.driver' => 'meta',
            'services.whatsapp_otp.graph_version' => 'v25.0',
            'services.whatsapp_otp.access_token' => 'test-token',
            'services.whatsapp_otp.phone_number_id' => '123456789',
            'services.whatsapp_otp.template_name' => 'registration_otp',
            'services.whatsapp_otp.template_language' => 'en_US',
        ]);

        Http::fake([
            'graph.facebook.com/*' => Http::response(['messages' => [['id' => 'wamid.test']]], 200),
        ]);

        app(WhatsAppOtpSender::class)->send('+201012345678', '654321');

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://graph.facebook.com/v25.0/123456789/messages'
                && $request['to'] === '201012345678'
                && $request['template']['name'] === 'registration_otp'
                && $request['template']['components'][0]['parameters'][0]['text'] === '654321'
                && $request['template']['components'][1]['parameters'][0]['text'] === '654321';
        });
    }
}
