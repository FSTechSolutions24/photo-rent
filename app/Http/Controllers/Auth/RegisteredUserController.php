<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Services\WhatsAppOtpSender;
use App\Support\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Throwable;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, WhatsAppOtpSender $sender)
    {
        $request->merge([
            'phone' => PhoneNumber::egyptian($request->input('phone')),
        ]);

        if ($previousId = $request->session()->pull('pending_registration_id')) {
            PendingRegistration::whereKey($previousId)->delete();
        }

        PendingRegistration::query()
            ->where('otp_expires_at', '<', now())
            ->where(function ($query) use ($request) {
                $query->where('email', $request->input('email'))
                    ->orWhere('phone', $request->input('phone'));
            })
            ->delete();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'not_regex:/[\r\n]/', 'max:255',
                Rule::unique('users', 'email'),
                Rule::unique('pending_registrations', 'email'),
            ],
            'phone' => [
                'required',
                'regex:/^\+201[0125][0-9]{8}$/',
                Rule::unique('users', 'phone'),
                Rule::unique('pending_registrations', 'phone'),
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex' => 'Enter a valid Egyptian WhatsApp number, such as 01012345678.',
            'phone.unique' => 'This mobile number is already registered or awaiting verification.',
        ]);

        $otp = (string) random_int(100000, 999999);
        $pending = PendingRegistration::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'otp_hash' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_last_sent_at' => now(),
            'registration_ip' => $request->ip(),
        ]);

        try {
            $sender->send($pending->phone, $otp);
        } catch (Throwable $exception) {
            $pending->delete();
            report($exception);

            throw ValidationException::withMessages([
                'phone' => 'We could not send a WhatsApp code right now. Please check the number and try again.',
            ]);
        }

        $request->session()->put('pending_registration_id', $pending->id);

        if (config('services.whatsapp_otp.driver') === 'log' && app()->environment(['local', 'testing'])) {
            $request->session()->flash('otp_debug_code', $otp);
        }

        return redirect()->route('registration.verify.notice')
            ->with('status', 'We sent a verification code to your WhatsApp number.');
    }
}
