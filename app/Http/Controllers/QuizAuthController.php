<?php

namespace App\Http\Controllers;

use App\Mail\QuizParticipantPasswordMail;
use App\Mail\QuizPasswordResetMail;
use App\Models\User;
use App\Services\OtpService;
use App\Services\PhoneService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class QuizAuthController extends Controller
{
    public function __construct(protected OtpService $otpService)
    {
    }

    /**
     * Send OTP to WhatsApp number for registration or login (supports India & International).
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'country_code' => 'nullable|string',
            'register' => 'nullable|boolean',
        ]);

        $normalized = PhoneService::normalize($request->input('phone'), $request->input('country_code'));
        $fullPhone = $normalized['full_phone'];
        $countryCode = $normalized['country_code'];
        $isRegistration = (bool) $request->input('register', false);

        try {
            $result = $this->otpService->sendOtp($fullPhone, $isRegistration, $countryCode);
            return response()->json($result);
        } catch (Exception $e) {
            $code = $e->getMessage();
            $status = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 400;

            return response()->json([
                'code' => $code,
                'message' => match ($code) {
                    'BADPHONE' => 'Enter a valid mobile number.',
                    'EXISTS' => 'This number is already registered. Use the Log in tab.',
                    'NOTFOUND' => 'No account for this number. Use the Register tab.',
                    default => $e->getMessage(),
                }
            ], $status);
        }
    }

    /**
     * Verify WhatsApp OTP and authenticate / register user.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'country_code' => 'nullable|string',
            'code' => 'required|string|size:6',
            'name' => 'nullable|string|max:100',
            'age' => 'nullable|integer|min:5|max:120',
            'locality' => 'nullable|string|max:100',
            'territory' => 'nullable|string|max:100',
            'register' => 'nullable|boolean',
        ]);

        $normalized = PhoneService::normalize($request->input('phone'), $request->input('country_code'));
        $fullPhone = $normalized['full_phone'];
        $countryCode = $normalized['country_code'];
        $local = $normalized['local_number'];
        $code = trim($request->input('code'));
        $isRegistration = (bool) $request->input('register', false);

        try {
            $this->otpService->verifyOtp($fullPhone, $code, $countryCode);
        } catch (Exception $e) {
            $errCode = $e->getMessage();
            return response()->json([
                'code' => $errCode,
                'message' => match ($errCode) {
                    'EXPIRED' => 'This code has expired. Request a new one.',
                    'INVALID' => 'That code is not correct. Check WhatsApp and try again.',
                    default => 'Verification failed. Try again.',
                }
            ], 422);
        }

        // OTP verified successfully - query existing user securely
        $user = PhoneService::findUser($fullPhone, $countryCode);

        if ($isRegistration) {
            if ($user) {
                return response()->json([
                    'code' => 'EXISTS',
                    'message' => 'This number is already registered. Use the Log in tab.',
                ], 409);
            }

            $countryMeta = PhoneService::getCountryByCode($countryCode);
            $defaultTerritory = ($countryCode !== '+91' && $countryMeta) ? $countryMeta['name'] : 'Kerala';

            $user = User::create([
                'name' => trim($request->input('name', 'Participant')),
                'age' => $request->input('age'),
                'locality' => trim($request->input('locality', '')),
                'territory' => trim($request->input('territory', $defaultTerritory)),
                'country_code' => $countryCode,
                'whatsapp_number' => $fullPhone,
                'whatsapp_verified' => true,
                'status' => 'active',
            ]);
        } else {
            if (!$user) {
                return response()->json([
                    'code' => 'NOTFOUND',
                    'message' => 'No account for this number. Use the Register tab.',
                ], 404);
            }

            if (!$user->whatsapp_verified) {
                $user->update(['whatsapp_verified' => true]);
            }
        }

        // Authenticate into web session
        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'territory' => $user->territory ?? $user->locality ?? 'Contestant',
            'locality' => $user->locality,
            'phone' => $user->whatsapp_number,
            'email' => $user->email,
        ]);
    }

    public function registerPassword(Request $request): JsonResponse
    {
        if ($request->filled('place')) {
            $place = trim($request->input('place'));
            if (!$request->filled('locality')) {
                $request->merge(['locality' => $place]);
            }
            if (!$request->filled('territory')) {
                $request->merge(['territory' => $place]);
            }
        }
        if (!$request->filled('country_code')) {
            $request->merge(['country_code' => '+91']);
        }
        if ($request->filled('dob') && !$request->filled('age')) {
            try {
                $calculatedAge = Carbon::parse($request->input('dob'))->age;
                $request->merge(['age' => max(1, min(120, $calculatedAge))]);
            } catch (\Exception $e) {
                $request->merge(['age' => null]);
            }
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'age' => 'nullable|integer|min:1|max:120',
            'dob' => 'nullable|string|max:30',
            'place' => 'nullable|string|max:100',
            'locality' => 'nullable|string|max:100',
            'territory' => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:10',
            'phone' => 'required|string|max:25',
            'email' => 'required|email|max:150',
            'password' => 'required|string|min:6',
        ]);

        $rawInputPhone = $request->input('phone');
        $rawInputCountryCode = $request->input('country_code', '+91');

        $normalized = PhoneService::normalize($rawInputPhone, $rawInputCountryCode);
        $countryCode = $normalized['country_code'];
        $localNumber = $normalized['local_number'];
        $fullPhone = $normalized['full_phone'];
        $rawDigits = $normalized['raw_digits'];

        // Validate phone format per selected country
        $phoneError = PhoneService::validate($rawInputPhone, $countryCode);
        if ($phoneError) {
            return response()->json([
                'code' => 'BADPHONE',
                'message' => $phoneError,
            ], 422);
        }

        $email = strtolower(trim($request->input('email')));
        $candidates = PhoneService::getPhoneCandidates($fullPhone, $countryCode);

        // Check if phone or email already registered using exact matching
        $existing = User::where(function ($q) use ($email, $candidates, $countryCode, $localNumber) {
            $q->where('email', $email)
              ->orWhereIn('whatsapp_number', $candidates);
            if (!empty($countryCode) && !empty($localNumber) && strlen($localNumber) >= 7) {
                $q->orWhere(function ($sub) use ($countryCode, $localNumber) {
                    $sub->where('country_code', $countryCode)
                        ->where('whatsapp_number', $localNumber);
                });
            }
        })->first();

        $countryMeta = PhoneService::getCountryByCode($countryCode);
        $defaultTerritory = ($countryCode !== '+91' && $countryMeta)
            ? $countryMeta['name']
            : ($request->input('place') ?: 'Kerala');

        if ($existing) {
            // If user previously registered without a password, allow setting it now and login
            if (empty($existing->password)) {
                $existing->update([
                    'password' => Hash::make($request->input('password')),
                    'country_code' => $countryCode,
                    'whatsapp_number' => $fullPhone,
                    'dob' => $request->input('dob') ?: $existing->dob,
                    'age' => $request->input('age') ?: $existing->age,
                    'place' => $request->input('place') ?: $existing->place,
                    'locality' => $request->input('locality') ?: $existing->locality,
                    'territory' => $request->input('territory') ?: ($existing->territory ?: $defaultTerritory),
                    'email' => $existing->email ?: $email,
                    'whatsapp_verified' => true,
                    'status' => 'active',
                ]);

                Auth::guard('web')->login($existing, true);
                if ($request->hasSession()) {
                    $request->session()->regenerate();
                }

                return response()->json([
                    'id' => $existing->id,
                    'name' => $existing->name,
                    'territory' => $existing->territory ?? $existing->locality ?? 'Contestant',
                    'locality' => $existing->locality,
                    'phone' => $existing->whatsapp_number,
                    'email' => $existing->email,
                ]);
            }

            return response()->json([
                'code' => 'EXISTS',
                'message' => 'This mobile number or email is already registered. Please log in.',
            ], 409);
        }

        $user = User::create([
            'name' => trim($request->input('name')),
            'dob' => $request->input('dob'),
            'age' => $request->input('age'),
            'place' => trim($request->input('place', $request->input('locality', ''))),
            'locality' => trim($request->input('locality', $request->input('place', ''))),
            'territory' => trim($request->input('territory', $defaultTerritory)),
            'country_code' => $countryCode,
            'whatsapp_number' => $fullPhone,
            'email' => $email,
            'password' => Hash::make($request->input('password')),
            'whatsapp_verified' => true,
            'status' => 'active',
        ]);

        Auth::guard('web')->login($user, true);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'territory' => $user->territory ?? $user->locality ?? 'Contestant',
            'locality' => $user->locality,
            'phone' => $user->whatsapp_number,
            'email' => $user->email,
        ]);
    }

    /**
     * Log in with mobile number (or email) and password.
     */
    public function loginPassword(Request $request): JsonResponse
    {
        $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
            'country_code' => 'nullable|string',
        ]);

        $identifier = trim($request->input('identifier'));
        $countryCode = trim($request->input('country_code', ''));

        $user = null;
        if (str_contains($identifier, '@')) {
            $user = User::where('email', strtolower($identifier))->first();
        } else {
            $user = PhoneService::findUser($identifier, $countryCode);
        }

        if (!$user) {
            return response()->json([
                'code' => 'NOTFOUND',
                'message' => 'No account found for this mobile number or email. Please register.',
            ], 404);
        }

        if (empty($user->password)) {
            return response()->json([
                'code' => 'NO_PASSWORD',
                'message' => 'No password is set on this account. Please register or use Forgot Password to set a password.',
            ], 400);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'code' => 'INVALID_PASSWORD',
                'message' => 'Incorrect password. Please try again.',
            ], 422);
        }

        Auth::guard('web')->login($user, true);
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'territory' => $user->territory ?? $user->locality ?? 'Contestant',
            'locality' => $user->locality,
            'phone' => $user->whatsapp_number,
            'email' => $user->email,
        ]);
    }

    /**
     * Send password reset link to user's email.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'identifier' => 'required|string',
            'country_code' => 'nullable|string',
        ]);

        $identifier = trim($request->input('identifier'));
        $countryCode = trim($request->input('country_code', ''));

        $user = null;
        if (str_contains($identifier, '@')) {
            $user = User::where('email', strtolower($identifier))->first();
        } else {
            $user = PhoneService::findUser($identifier, $countryCode);
        }

        if (!$user) {
            return response()->json([
                'code' => 'NOTFOUND',
                'message' => 'No account found with this information.',
            ], 404);
        }

        if (empty($user->email)) {
            return response()->json([
                'code' => 'NO_EMAIL',
                'message' => 'No email address is linked to this account. Please log in using WhatsApp OTP.',
            ], 400);
        }

        // Generate a new secure 8-character password using cryptographically secure random_int
        $newPassword = Str::lower(Str::random(4)) . random_int(1000, 9999);
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Keep token in password_reset_tokens for audit / compatibility
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        // Mask email for user privacy (e.g. j***n@gmail.com)
        $parts = explode('@', $user->email);
        $namePart = $parts[0];
        $maskedName = strlen($namePart) > 2 ? substr($namePart, 0, 1) . str_repeat('*', strlen($namePart) - 2) . substr($namePart, -1) : $namePart . '**';
        $maskedEmail = $maskedName . '@' . ($parts[1] ?? '');

        try {
            Mail::to($user->email)->send(new QuizParticipantPasswordMail($user, $newPassword, url('/quiz'), 'forgot_password'));
        } catch (Exception $e) {
            Log::error("Failed to send password email: " . $e->getMessage());
            return response()->json([
                'code' => 'MAIL_ERROR',
                'message' => 'Unable to send password email. Please try again later.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "പുതിയ പാസ്‌വേഡ് നിങ്ങളുടെ ഇമെയിലിലേക്ക് ({$maskedEmail}) അയച്ചിട്ടുണ്ട്. ഇൻബോക്സ് പരിശോധിച്ച് ലോഗിൻ ചെയ്യുക.",
        ]);
    }

    /**
     * Reset password using the emailed token.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $email = strtolower(trim($request->input('email')));
        $token = trim($request->input('token'));
        $password = $request->input('password');

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return response()->json([
                'code' => 'EXPIRED',
                'message' => 'This password reset link is invalid or has expired. Please request a new one.',
            ], 422);
        }

        // Check 60 minute expiry
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return response()->json([
                'code' => 'EXPIRED',
                'message' => 'This password reset link has expired. Please request a new one.',
            ], 422);
        }

        // Verify token hash
        if (!Hash::check($token, $record->token)) {
            return response()->json([
                'code' => 'INVALID',
                'message' => 'Invalid password reset token.',
            ], 422);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'code' => 'NOTFOUND',
                'message' => 'User account not found.',
            ], 404);
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Log user in automatically
        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'territory' => $user->territory ?? $user->locality ?? 'Contestant',
            'locality' => $user->locality,
            'phone' => $user->whatsapp_number,
            'email' => $user->email,
        ]);
    }

    /**
     * Get currently authenticated user details.
     */
    public function me(): JsonResponse
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return response()->json(['user' => null]);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'territory' => $user->territory ?? $user->locality ?? 'Contestant',
                'locality' => $user->locality,
                'phone' => $user->whatsapp_number,
                'email' => $user->email,
            ]
        ]);
    }

    /**
     * Log out participant.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true]);
    }
}
