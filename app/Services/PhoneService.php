<?php

namespace App\Services;

class PhoneService
{
    /**
     * Standard curated country dial code list with flags, limits, and placeholders.
     */
    public const COUNTRIES = [
        [
            'code' => '+91',
            'name' => 'India',
            'flag' => '🇮🇳',
            'iso' => 'IN',
            'min_len' => 10,
            'max_len' => 10,
            'placeholder' => '98765 43210',
            'hint' => '10 digits'
        ],
        [
            'code' => '+971',
            'name' => 'United Arab Emirates',
            'flag' => '🇦🇪',
            'iso' => 'AE',
            'min_len' => 9,
            'max_len' => 9,
            'placeholder' => '50 123 4567',
            'hint' => '9 digits (e.g. 50 123 4567)'
        ],
        [
            'code' => '+966',
            'name' => 'Saudi Arabia',
            'flag' => '🇸🇦',
            'iso' => 'SA',
            'min_len' => 9,
            'max_len' => 9,
            'placeholder' => '55 123 4567',
            'hint' => '9 digits (e.g. 55 123 4567)'
        ],
        [
            'code' => '+974',
            'name' => 'Qatar',
            'flag' => '🇶🇦',
            'iso' => 'QA',
            'min_len' => 8,
            'max_len' => 8,
            'placeholder' => '5512 3456',
            'hint' => '8 digits'
        ],
        [
            'code' => '+965',
            'name' => 'Kuwait',
            'flag' => '🇰🇼',
            'iso' => 'KW',
            'min_len' => 8,
            'max_len' => 8,
            'placeholder' => '5012 3456',
            'hint' => '8 digits'
        ],
        [
            'code' => '+968',
            'name' => 'Oman',
            'flag' => '🇴🇲',
            'iso' => 'OM',
            'min_len' => 8,
            'max_len' => 8,
            'placeholder' => '9123 4567',
            'hint' => '8 digits'
        ],
        [
            'code' => '+973',
            'name' => 'Bahrain',
            'flag' => '🇧🇭',
            'iso' => 'BH',
            'min_len' => 8,
            'max_len' => 8,
            'placeholder' => '3612 3456',
            'hint' => '8 digits'
        ],
        [
            'code' => '+44',
            'name' => 'United Kingdom',
            'flag' => '🇬🇧',
            'iso' => 'GB',
            'min_len' => 10,
            'max_len' => 10,
            'placeholder' => '7911 123456',
            'hint' => '10 digits'
        ],
        [
            'code' => '+1',
            'name' => 'USA / Canada',
            'flag' => '🇺🇸',
            'iso' => 'US',
            'min_len' => 10,
            'max_len' => 10,
            'placeholder' => '202 555 0143',
            'hint' => '10 digits'
        ],
        [
            'code' => '+60',
            'name' => 'Malaysia',
            'flag' => '🇲🇾',
            'iso' => 'MY',
            'min_len' => 9,
            'max_len' => 10,
            'placeholder' => '12 345 6789',
            'hint' => '9-10 digits'
        ],
        [
            'code' => '+65',
            'name' => 'Singapore',
            'flag' => '🇸🇬',
            'iso' => 'SG',
            'min_len' => 8,
            'max_len' => 8,
            'placeholder' => '8123 4567',
            'hint' => '8 digits'
        ],
        [
            'code' => '+61',
            'name' => 'Australia',
            'flag' => '🇦🇺',
            'iso' => 'AU',
            'min_len' => 9,
            'max_len' => 9,
            'placeholder' => '412 345 678',
            'hint' => '9 digits'
        ],
        [
            'code' => '+64',
            'name' => 'New Zealand',
            'flag' => '🇳🇿',
            'iso' => 'NZ',
            'min_len' => 8,
            'max_len' => 10,
            'placeholder' => '21 123 4567',
            'hint' => '8-10 digits'
        ],
        [
            'code' => '+49',
            'name' => 'Germany',
            'flag' => '🇩🇪',
            'iso' => 'DE',
            'min_len' => 10,
            'max_len' => 11,
            'placeholder' => '151 12345678',
            'hint' => '10-11 digits'
        ],
        [
            'code' => '+33',
            'name' => 'France',
            'flag' => '🇫🇷',
            'iso' => 'FR',
            'min_len' => 9,
            'max_len' => 9,
            'placeholder' => '6 12 34 56 78',
            'hint' => '9 digits'
        ],
        [
            'code' => '+39',
            'name' => 'Italy',
            'flag' => '🇮🇹',
            'iso' => 'IT',
            'min_len' => 9,
            'max_len' => 10,
            'placeholder' => '312 345 6789',
            'hint' => '9-10 digits'
        ],
        [
            'code' => '+353',
            'name' => 'Ireland',
            'flag' => '🇮🇪',
            'iso' => 'IE',
            'min_len' => 9,
            'max_len' => 9,
            'placeholder' => '85 123 4567',
            'hint' => '9 digits'
        ],
        [
            'code' => '+960',
            'name' => 'Maldives',
            'flag' => '🇲🇻',
            'iso' => 'MV',
            'min_len' => 7,
            'max_len' => 7,
            'placeholder' => '712 3456',
            'hint' => '7 digits'
        ],
        [
            'code' => '+94',
            'name' => 'Sri Lanka',
            'flag' => '🇱🇰',
            'iso' => 'LK',
            'min_len' => 9,
            'max_len' => 9,
            'placeholder' => '71 234 5678',
            'hint' => '9 digits'
        ],
        [
            'code' => '+92',
            'name' => 'Pakistan',
            'flag' => '🇵🇰',
            'iso' => 'PK',
            'min_len' => 10,
            'max_len' => 10,
            'placeholder' => '301 2345678',
            'hint' => '10 digits'
        ],
        [
            'code' => '+880',
            'name' => 'Bangladesh',
            'flag' => '🇧🇩',
            'iso' => 'BD',
            'min_len' => 10,
            'max_len' => 10,
            'placeholder' => '1712 345678',
            'hint' => '10 digits'
        ],
        [
            'code' => '+20',
            'name' => 'Egypt',
            'flag' => '🇪🇬',
            'iso' => 'EG',
            'min_len' => 10,
            'max_len' => 10,
            'placeholder' => '10 1234 5678',
            'hint' => '10 digits'
        ],
        [
            'code' => '+90',
            'name' => 'Turkey',
            'flag' => '🇹🇷',
            'iso' => 'TR',
            'min_len' => 10,
            'max_len' => 10,
            'placeholder' => '501 234 5678',
            'hint' => '10 digits'
        ],
        [
            'code' => '+27',
            'name' => 'South Africa',
            'flag' => '🇿🇦',
            'iso' => 'ZA',
            'min_len' => 9,
            'max_len' => 9,
            'placeholder' => '71 234 5678',
            'hint' => '9 digits'
        ],
    ];

    /**
     * Normalize an input phone and optional country code into structured parts.
     *
     * @param string $phone
     * @param string|null $countryCode
     * @return array{country_code: string, local_number: string, full_phone: string, raw_digits: string}
     */
    public static function normalize(string $phone, ?string $countryCode = null): array
    {
        $trimmed = trim($phone);

        // Replace leading 00 with + (international prefix)
        if (str_starts_with($trimmed, '00')) {
            $trimmed = '+' . substr($trimmed, 2);
        }

        // Clean raw digits for searching
        $rawDigits = preg_replace('/\D/', '', $trimmed);

        // Determine if phone itself starts with '+'
        $resolvedCountryCode = null;
        $localDigits = '';

        if (str_starts_with($trimmed, '+')) {
            // Find matching dial code (longest match first)
            $dialCodes = array_column(self::COUNTRIES, 'code');
            usort($dialCodes, fn ($a, $b) => strlen($b) <=> strlen($a));

            foreach ($dialCodes as $code) {
                if (str_starts_with($trimmed, $code)) {
                    $resolvedCountryCode = $code;
                    $localDigits = preg_replace('/\D/', '', substr($trimmed, strlen($code)));
                    break;
                }
            }

            // If not found in curated list, extract arbitrary +1 to +9999
            if (!$resolvedCountryCode && preg_match('/^\+(\d{1,4})(.*)$/', $trimmed, $m)) {
                $resolvedCountryCode = '+' . $m[1];
                $localDigits = preg_replace('/\D/', '', $m[2]);
            }
        }

        // If no country code parsed from phone, use supplied $countryCode or default to +91
        if (!$resolvedCountryCode) {
            $c = trim((string) $countryCode);
            if ($c !== '') {
                $resolvedCountryCode = str_starts_with($c, '+') ? $c : ('+' . $c);
            } else {
                // If 10 digits starting with 6,7,8,9, it is an Indian mobile number
                if (strlen($rawDigits) === 10 && in_array(substr($rawDigits, 0, 1), ['6','7','8','9'], true)) {
                    $resolvedCountryCode = '+91';
                } else {
                    $resolvedCountryCode = '+91';
                }
            }

            $dialDigits = ltrim($resolvedCountryCode, '+');
            $localDigits = $rawDigits;

            // If user typed the country dial digits at start of phone (e.g. 971501234567 or 919876543210)
            if (strlen($dialDigits) > 0 && str_starts_with($localDigits, $dialDigits) && strlen($localDigits) > (strlen($dialDigits) + 5)) {
                $localDigits = substr($localDigits, strlen($dialDigits));
            }
        }

        // Strip leading trunk 0 if present (e.g. UAE 0501234567 -> 501234567, Saudi 0551234567 -> 551234567, UK 07911123456 -> 7911123456)
        if (str_starts_with($localDigits, '0') && strlen($localDigits) > 5) {
            $localDigits = ltrim($localDigits, '0');
        }

        $fullPhone = $resolvedCountryCode . $localDigits;

        return [
            'country_code' => $resolvedCountryCode,
            'local_number' => $localDigits,
            'full_phone' => $fullPhone,
            'raw_digits' => $rawDigits,
        ];
    }

    /**
     * Validate the normalized phone number.
     * Returns an error message string if invalid, or null if valid.
     */
    public static function validate(string $phone, ?string $countryCode = null): ?string
    {
        $norm = self::normalize($phone, $countryCode);
        $local = $norm['local_number'];
        $code = $norm['country_code'];

        if (empty($local)) {
            return 'Please enter a valid mobile number.';
        }

        $meta = self::getCountryByCode($code);

        if ($code === '+91') {
            if (strlen($local) !== 10) {
                return 'Enter a 10-digit Indian mobile number.';
            }
            if (!in_array(substr($local, 0, 1), ['6','7','8','9'], true)) {
                return 'Indian mobile numbers must start with 6, 7, 8, or 9.';
            }
            return null;
        }

        if ($meta) {
            $len = strlen($local);
            if ($len < $meta['min_len'] || $len > $meta['max_len']) {
                return "Enter a valid {$meta['name']} mobile number ({$meta['hint']}).";
            }
            return null;
        }

        // General international fallback (ITU standard: 6 to 15 digits)
        if (strlen($local) < 6 || strlen($local) > 15) {
            return 'Please enter a valid mobile number (6 to 15 digits).';
        }

        return null;
    }

    /**
     * Find country details by dial code.
     */
    public static function getCountryByCode(string $code): ?array
    {
        $formatted = str_starts_with($code, '+') ? $code : ('+' . $code);
        foreach (self::COUNTRIES as $country) {
            if ($country['code'] === $formatted) {
                return $country;
            }
        }
        return null;
    }

    /**
     * Get all country options formatted for select dropdowns.
     */
    public static function getSelectOptions(): array
    {
        $options = [];
        foreach (self::COUNTRIES as $c) {
            $options[$c['code']] = "{$c['flag']} {$c['name']} ({$c['code']})";
        }
        return $options;
    }
}
