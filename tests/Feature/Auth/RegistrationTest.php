<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_requires_whatsapp_verification()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '01012345678',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('pending_registrations', [
            'email' => 'test@example.com',
            'phone' => '+201012345678',
        ]);
        $response->assertRedirect(route('registration.verify.notice'));
        $response->assertSessionHas('otp_debug_code');
    }

    public function test_user_is_created_after_the_whatsapp_code_is_confirmed()
    {
        $registration = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+20 10 1234 5678',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $otp = $registration->getSession()->get('otp_debug_code');
        $response = $this->post(route('registration.verify'), ['otp' => $otp]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'phone' => '+201012345678',
        ]);
        $this->assertNotNull(User::where('email', 'test@example.com')->value('phone_verified_at'));
        $this->assertDatabaseMissing('pending_registrations', ['email' => 'test@example.com']);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_a_mobile_number_can_only_belong_to_one_user()
    {
        User::factory()->create(['phone' => '+201012345678']);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Another User',
            'email' => 'another@example.com',
            'phone' => '01012345678',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('phone');
        $this->assertDatabaseMissing('pending_registrations', ['email' => 'another@example.com']);
    }
}
