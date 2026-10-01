<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\WhatsAppOtpSender;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class RegistrationOtpController extends Controller
{
    private const MAX_ATTEMPTS = 5;
    private const MAX_SENDS = 5;

    public function show(Request $request)
    {
        $pending = $this->pending($request);

        if (! $pending) {
            return redirect()->route('register')
                ->withErrors(['phone' => 'Start registration again to receive a new verification code.']);
        }

        return view('auth.verify-whatsapp', compact('pending'));
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.digits' => 'Enter the 6-digit code sent to WhatsApp.',
        ]);

        $pending = $this->pending($request);

        if (! $pending) {
            return redirect()->route('register')
                ->withErrors(['phone' => 'This registration session is no longer available. Please register again.']);
        }

        if ($pending->otp_expires_at->isPast()) {
            throw ValidationException::withMessages([
                'otp' => 'This code has expired. Request a new code below.',
            ]);
        }

        if ($pending->otp_attempts >= self::MAX_ATTEMPTS) {
            throw ValidationException::withMessages([
                'otp' => 'Too many incorrect attempts. Request a new code below.',
            ]);
        }

        if (! Hash::check($validated['otp'], $pending->otp_hash)) {
            $pending->increment('otp_attempts');
            $remaining = max(0, self::MAX_ATTEMPTS - $pending->otp_attempts);

            throw ValidationException::withMessages([
                'otp' => "The verification code is incorrect. {$remaining} attempt(s) remaining.",
            ]);
        }

        $user = DB::transaction(function () use ($pending, $validated) {
            $locked = PendingRegistration::query()->lockForUpdate()->findOrFail($pending->id);

            if ($locked->otp_expires_at->isPast()) {
                throw ValidationException::withMessages(['otp' => 'This code has expired.']);
            }

            if ($locked->otp_attempts >= self::MAX_ATTEMPTS || ! Hash::check($validated['otp'], $locked->otp_hash)) {
                throw ValidationException::withMessages(['otp' => 'This verification code is no longer valid.']);
            }

            $user = User::create([
                'name' => $locked->name,
                'email' => $locked->email,
                'phone' => $locked->phone,
                'phone_verified_at' => now(),
                'password' => $locked->password,
            ]);

            $locked->delete();

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);
        $request->session()->forget('pending_registration_id');
        $request->session()->regenerate();

        return redirect(RouteServiceProvider::HOME);
    }

    public function resend(Request $request, WhatsAppOtpSender $sender)
    {
        $pending = $this->pending($request);

        if (! $pending) {
            return redirect()->route('register');
        }

        if ($pending->otp_send_count >= self::MAX_SENDS) {
            throw ValidationException::withMessages([
                'otp' => 'The resend limit was reached. Please wait and register again later.',
            ]);
        }

        if ($pending->otp_last_sent_at->greaterThan(now()->subMinute())) {
            throw ValidationException::withMessages([
                'otp' => 'Please wait one minute before requesting another code.',
            ]);
        }

        $otp = (string) random_int(100000, 999999);

        try {
            $sender->send($pending->phone, $otp);
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'otp' => 'We could not resend the WhatsApp code right now. Please try again shortly.',
            ]);
        }

        $pending->update([
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts' => 0,
            'otp_send_count' => $pending->otp_send_count + 1,
            'otp_last_sent_at' => now(),
        ]);

        if (config('services.whatsapp_otp.driver') === 'log' && app()->environment(['local', 'testing'])) {
            $request->session()->flash('otp_debug_code', $otp);
        }

        return back()->with('status', 'A new WhatsApp verification code was sent.');
    }

    private function pending(Request $request): ?PendingRegistration
    {
        $id = $request->session()->get('pending_registration_id');

        return $id ? PendingRegistration::find($id) : null;
    }
}
