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
     * Clean and normalize a phone number with country support.
     */
    public function normalizePhone(string $phone, ?string $countryCode = null): string
    {
        $res = PhoneService::normalize($phone, $countryCode);
        return $res['full_phone'];
    }

    /**
     * Get the default OTP code if configured.
     * Returns null if default OTP is disabled (e.g. once SMS gateway is integrated).
     */
    public function getDefaultOtp(): ?string
    {
        $default = config('services.otp.default_code', env('DEFAULT_OTP', '123456'));

        if ($default === null || $default === false || $default === '' || in_array(strtolower((string) $default), ['false', 'null', 'none', 'off', 'disabled'], true)) {
            return null;
        }

        return (string) $default;
    }

    /**
     * Send OTP to a WhatsApp number (supports India and international numbers).
     *
     * @param string $phone Phone number
     * @param bool $isRegistration True if user is registering, false if logging in
     * @param string|null $countryCode Dial code (e.g. +91, +971)
     * @return array
     * @throws Exception
     */
    public function sendOtp(string $phone, bool $isRegistration, ?string $countryCode = null): array
    {
        $res = PhoneService::normalize($phone, $countryCode);
        $fullPhone = $res['full_phone'];
        $local = $res['local_number'];
        $code = $res['country_code'];

        $phoneError = PhoneService::validate($phone, $code);
        if ($phoneError) {
            throw new Exception("BADPHONE", 422);
        }

        // Check user existence across both international and local formats
        $userExists = User::where('whatsapp_number', $fullPhone)
            ->orWhere('whatsapp_number', ltrim($fullPhone, '+'))
            ->when($code === '+91', function ($q) use ($local) {
                $q->orWhere('whatsapp_number', $local)
                  ->orWhere('whatsapp_number', '+91' . $local);
            })
            ->when($code !== '+91', function ($q) use ($code, $local) {
                $q->orWhere(function ($sub) use ($code, $local) {
                    $sub->where('country_code', $code)
                        ->where('whatsapp_number', 'like', "%{$local}");
                });
            })
            ->exists();

        if ($isRegistration && $userExists) {
            throw new Exception("EXISTS", 409);
        }

        if (!$isRegistration && !$userExists) {
            throw new Exception("NOTFOUND", 404);
        }

        $normalized = $fullPhone;

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
        // Uses default OTP if configured (e.g. until SMS gateway integration), or random 6-digit code
        $defaultOtp = $this->getDefaultOtp();
        $code = $defaultOtp ?: str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $otpKey = "quiz_otp_{$normalized}";
        $attemptsKey = "quiz_otp_attempts_{$normalized}";

        Cache::put($otpKey, $code, now()->addMinutes(self::OTP_EXPIRY_MINUTES));
        Cache::put($cooldownKey, now()->timestamp, now()->addSeconds(self::RESEND_COOLDOWN_SECONDS));
        Cache::forget($attemptsKey);

        // Log OTP for easy verification/debugging
        Log::info("WhatsApp OTP for {$normalized}: {$code} (Reg: " . ($isRegistration ? 'yes' : 'no') . ")");

        // Note: If a third-party WhatsApp gateway (Wati/Interakt/Twilio/UltraMsg) credentials
        // are configured in .env, dispatch the HTTP request here.

        $result = [
            'success' => true,
            'message' => 'OTP sent successfully to your WhatsApp number.',
            'cooldown' => self::RESEND_COOLDOWN_SECONDS,
        ];

        if ($defaultOtp) {
            $result['demo_code'] = $defaultOtp;
        }

        return $result;
    }

    /**
     * Verify the provided OTP code.
     *
     * @param string $phone
     * @param string $code
     * @param string|null $countryCode
     * @return bool
     * @throws Exception
     */
    public function verifyOtp(string $phone, string $code, ?string $countryCode = null): bool
    {
        $normalized = $this->normalizePhone($phone, $countryCode);
        $otpKey = "quiz_otp_{$normalized}";
        $attemptsKey = "quiz_otp_attempts_{$normalized}";

        $attempts = (int) Cache::get($attemptsKey, 0);
        if ($attempts >= self::MAX_ATTEMPTS) {
            Cache::forget($otpKey);
            throw new Exception("EXPIRED", 422);
        }

        $storedCode = Cache::get($otpKey);
        $defaultOtp = $this->getDefaultOtp();

        // Valid code can be the stored code from cache, or fallback to default OTP if configured
        $validCode = $storedCode ?? $defaultOtp;

        if (!$validCode) {
            throw new Exception("EXPIRED", 422);
        }

        $submittedCode = trim($code);
        $isValid = ($submittedCode === trim((string) $validCode))
            || ($defaultOtp !== null && $submittedCode === trim((string) $defaultOtp));

        if (!$isValid) {
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
