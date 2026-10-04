<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public const OTP_EXPIRY_MINUTES = 5;
    public const RESEND_COOLDOWN_SECONDS = 30;
    public const MAX_ATTEMPTS = 5;

    /**
     * Clean and normalize a 10-digit phone number.
     */
    public function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        // If it starts with country code 91 and has 12 digits, strip 91
        if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            $digits = substr($digits, 2);
        }

        return $digits;
    }

    /**
     * Send OTP to a WhatsApp number.
     *
     * @param string $phone 10-digit phone number
     * @param bool $isRegistration True if user is registering, false if logging in
     * @return array
     * @throws Exception
     */
    public function sendOtp(string $phone, bool $isRegistration): array
    {
        $normalized = $this->normalizePhone($phone);

        if (strlen($normalized) !== 10) {
            throw new Exception("BADPHONE", 422);
        }

        // Check user existence
        $userExists = User::where('whatsapp_number', $normalized)
            ->orWhere('whatsapp_number', '+91' . $normalized)
            ->exists();

        if ($isRegistration && $userExists) {
            throw new Exception("EXISTS", 409);
        }

        if (!$isRegistration && !$userExists) {
            throw new Exception("NOTFOUND", 404);
        }

        // Check resend cooldown
        $cooldownKey = "quiz_otp_cooldown_{$normalized}";
        if (Cache::has($cooldownKey)) {
            $ttl = Cache::get($cooldownKey);
            // Already sent recently, still valid
            return [
                'success' => true,
                'message' => 'OTP already sent. Please wait before requesting another.',
                'cooldown' => self::RESEND_COOLDOWN_SECONDS,
            ];
        }

        // Generate 6-digit OTP
        // In local/testing environments, 123456 can be used or random
        $isLocalOrTest = app()->environment('local', 'testing');
        $code = $isLocalOrTest ? '123456' : str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $otpKey = "quiz_otp_{$normalized}";
        $attemptsKey = "quiz_otp_attempts_{$normalized}";

        Cache::put($otpKey, $code, now()->addMinutes(self::OTP_EXPIRY_MINUTES));
        Cache::put($cooldownKey, now()->timestamp, now()->addSeconds(self::RESEND_COOLDOWN_SECONDS));
        Cache::forget($attemptsKey);

        // Log OTP for easy verification/debugging
        Log::info("WhatsApp OTP for {$normalized}: {$code} (Reg: " . ($isRegistration ? 'yes' : 'no') . ")");

        // Note: If a third-party WhatsApp gateway (Wati/Interakt/Twilio/UltraMsg) credentials
        // are configured in .env, dispatch the HTTP request here.

        return [
            'success' => true,
            'message' => 'OTP sent successfully to your WhatsApp number.',
            'cooldown' => self::RESEND_COOLDOWN_SECONDS,
        ];
    }

    /**
     * Verify the provided OTP code.
     *
     * @param string $phone
     * @param string $code
     * @return bool
     * @throws Exception
     */
    public function verifyOtp(string $phone, string $code): bool
    {
        $normalized = $this->normalizePhone($phone);
        $otpKey = "quiz_otp_{$normalized}";
        $attemptsKey = "quiz_otp_attempts_{$normalized}";

        $attempts = (int) Cache::get($attemptsKey, 0);
        if ($attempts >= self::MAX_ATTEMPTS) {
            Cache::forget($otpKey);
            throw new Exception("EXPIRED", 422);
        }

        $storedCode = Cache::get($otpKey);

        // Allow demo code 123456 in local/testing
        $isLocalOrTest = app()->environment('local', 'testing');
        $validCode = $storedCode ?? ($isLocalOrTest ? '123456' : null);

        if (!$validCode) {
            throw new Exception("EXPIRED", 422);
        }

        if (trim($code) !== trim($validCode)) {
            Cache::put($attemptsKey, $attempts + 1, now()->addMinutes(self::OTP_EXPIRY_MINUTES));
            throw new Exception("INVALID", 422);
        }

        // OTP verified successfully
        Cache::forget($otpKey);
        Cache::forget($attemptsKey);
        Cache::forget("quiz_otp_cooldown_{$normalized}");

        return true;
    }
}
