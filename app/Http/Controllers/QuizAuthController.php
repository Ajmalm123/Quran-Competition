<?php

namespace App\Http\Controllers;

use App\Mail\QuizPasswordResetMail;
use App\Models\User;
use App\Services\OtpService;
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
     * Send OTP to WhatsApp number for registration or login (Indian numbers).
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'register' => 'nullable|boolean',
        ]);

        $phone = $this->otpService->normalizePhone($request->input('phone'));
        $isRegistration = (bool) $request->input('register', false);

        try {
            $result = $this->otpService->sendOtp($phone, $isRegistration);
            return response()->json($result);
        } catch (Exception $e) {
            $code = $e->getMessage();
            $status = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 400;

            return response()->json([
                'code' => $code,
                'message' => match ($code) {
                    'BADPHONE' => 'Enter a 10-digit WhatsApp number.',
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
            'code' => 'required|string|size:6',
            'name' => 'nullable|string|max:100',
            'age' => 'nullable|integer|min:5|max:120',
            'locality' => 'nullable|string|max:100',
            'territory' => 'nullable|string|max:100',
            'register' => 'nullable|boolean',
        ]);

        $phone = $this->otpService->normalizePhone($request->input('phone'));
        $code = trim($request->input('code'));
        $isRegistration = (bool) $request->input('register', false);

        try {
            $this->otpService->verifyOtp($phone, $code);
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

        // OTP verified successfully
        $user = User::where('whatsapp_number', $phone)
            ->orWhere('whatsapp_number', '+91' . $phone)
            ->first();

        if ($isRegistration) {
            if ($user) {
                return response()->json([
                    'code' => 'EXISTS',
                    'message' => 'This number is already registered. Use the Log in tab.',
                ], 409);
            }

            $user = User::create([
                'name' => trim($request->input('name', 'Participant')),
                'age' => $request->input('age'),
                'locality' => trim($request->input('locality', '')),
                'territory' => trim($request->input('territory', '')),
                'country_code' => '+91',
                'whatsapp_number' => '+91' . $phone,
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
        ]);
    }

    /**
     * Register international user with password and email.
     */
    public function registerPassword(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'age' => 'required|integer|min:5|max:120',
            'locality' => 'required|string|max:100',
            'territory' => 'required|string|max:100',
            'country_code' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:150',
            'password' => 'required|string|min:6',
        ]);

        $countryCode = trim($request->input('country_code'));
        if (!str_starts_with($countryCode, '+')) {
            $countryCode = '+' . $countryCode;
        }

        $rawPhone = preg_replace('/\D/', '', $request->input('phone'));
        $fullPhone = $countryCode . $rawPhone;
        $email = strtolower(trim($request->input('email')));

        // Check if phone or email already registered
        $existing = User::where('whatsapp_number', $fullPhone)
            ->orWhere('whatsapp_number', $rawPhone)
            ->orWhere('email', $email)
            ->first();

        if ($existing) {
            return response()->json([
                'code' => 'EXISTS',
                'message' => 'This mobile number or email is already registered. Please log in.',
            ], 409);
        }

        $user = User::create([
            'name' => trim($request->input('name')),
            'age' => $request->input('age'),
            'locality' => trim($request->input('locality')),
            'territory' => trim($request->input('territory')),
            'country_code' => $countryCode,
            'whatsapp_number' => $fullPhone,
            'email' => $email,
            'password' => Hash::make($request->input('password')),
            'whatsapp_verified' => true,
            'status' => 'active',
        ]);

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
        if ($countryCode && !str_starts_with($countryCode, '+')) {
            $countryCode = '+' . $countryCode;
        }

        $user = null;
        if (str_contains($identifier, '@')) {
            $user = User::where('email', strtolower($identifier))->first();
        } else {
            $rawPhone = preg_replace('/\D/', '', $identifier);
            $fullPhone = $countryCode ? ($countryCode . $rawPhone) : $identifier;

            $user = User::where('whatsapp_number', $fullPhone)
                ->orWhere('whatsapp_number', $rawPhone)
                ->orWhere('whatsapp_number', '+' . $rawPhone)
                ->orWhere('whatsapp_number', 'like', "%{$rawPhone}%")
                ->first();
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
                'message' => 'No password is set on this account. Please use WhatsApp OTP to log in.',
            ], 400);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'code' => 'INVALID_PASSWORD',
                'message' => 'Incorrect password. Please try again.',
            ], 422);
        }

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
     * Send password reset link to user's email.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = trim($request->input('identifier'));

        $user = null;
        if (str_contains($identifier, '@')) {
            $user = User::where('email', strtolower($identifier))->first();
        } else {
            $rawPhone = preg_replace('/\D/', '', $identifier);
            $user = User::where('whatsapp_number', 'like', "%{$rawPhone}%")->first();
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

        // Generate reset token and store in password_reset_tokens
        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        $resetUrl = url('/quiz?reset_token=' . $token . '&email=' . urlencode($user->email));

        try {
            Mail::to($user->email)->send(new QuizPasswordResetMail($user, $resetUrl));
        } catch (Exception $e) {
            Log::error("Failed to send password reset email: " . $e->getMessage());
            return response()->json([
                'code' => 'MAIL_ERROR',
                'message' => 'Unable to send password reset email. Please try again later.',
            ], 500);
        }

        // Mask email for user privacy (e.g. j***n@gmail.com)
        $parts = explode('@', $user->email);
        $namePart = $parts[0];
        $maskedName = strlen($namePart) > 2 ? substr($namePart, 0, 1) . str_repeat('*', strlen($namePart) - 2) . substr($namePart, -1) : $namePart . '**';
        $maskedEmail = $maskedName . '@' . ($parts[1] ?? '');

        return response()->json([
            'success' => true,
            'message' => "A password reset link has been sent to {$maskedEmail}. Please check your inbox.",
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
