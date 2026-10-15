<?php

namespace App\Services;

use App\Models\User;

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
            'hint' => '10 digits',
            'group' => 'India',
        ],
        [
            'code' => '+971',
            'name' => 'United Arab Emirates',
            'flag' => '🇦🇪',
            'iso' => 'AE',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Gulf',
        ],
        [
            'code' => '+966',
            'name' => 'Saudi Arabia',
            'flag' => '🇸🇦',
            'iso' => 'SA',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Gulf',
        ],
        [
            'code' => '+974',
            'name' => 'Qatar',
            'flag' => '🇶🇦',
            'iso' => 'QA',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Gulf',
        ],
        [
            'code' => '+965',
            'name' => 'Kuwait',
            'flag' => '🇰🇼',
            'iso' => 'KW',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Gulf',
        ],
        [
            'code' => '+968',
            'name' => 'Oman',
            'flag' => '🇴🇲',
            'iso' => 'OM',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Gulf',
        ],
        [
            'code' => '+973',
            'name' => 'Bahrain',
            'flag' => '🇧🇭',
            'iso' => 'BH',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Gulf',
        ],
        [
            'code' => '+93',
            'name' => 'Afghanistan',
            'flag' => '🇦🇫',
            'iso' => 'AF',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+355',
            'name' => 'Albania',
            'flag' => '🇦🇱',
            'iso' => 'AL',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+213',
            'name' => 'Algeria',
            'flag' => '🇩🇿',
            'iso' => 'DZ',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+376',
            'name' => 'Andorra',
            'flag' => '🇦🇩',
            'iso' => 'AD',
            'min_len' => 6,
            'max_len' => 9,
            'hint' => '6-9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+244',
            'name' => 'Angola',
            'flag' => '🇦🇴',
            'iso' => 'AO',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1268',
            'name' => 'Antigua and Barbuda',
            'flag' => '🇦🇬',
            'iso' => 'AG',
            'min_len' => 7,
            'max_len' => 10,
            'hint' => '7-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+54',
            'name' => 'Argentina',
            'flag' => '🇦🇷',
            'iso' => 'AR',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+374',
            'name' => 'Armenia',
            'flag' => '🇦🇲',
            'iso' => 'AM',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+61',
            'name' => 'Australia',
            'flag' => '🇦🇺',
            'iso' => 'AU',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+43',
            'name' => 'Austria',
            'flag' => '🇦🇹',
            'iso' => 'AT',
            'min_len' => 10,
            'max_len' => 13,
            'hint' => '10-13 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+994',
            'name' => 'Azerbaijan',
            'flag' => '🇦🇿',
            'iso' => 'AZ',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1242',
            'name' => 'Bahamas',
            'flag' => '🇧🇸',
            'iso' => 'BS',
            'min_len' => 7,
            'max_len' => 10,
            'hint' => '7-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+880',
            'name' => 'Bangladesh',
            'flag' => '🇧🇩',
            'iso' => 'BD',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1246',
            'name' => 'Barbados',
            'flag' => '🇧🇧',
            'iso' => 'BB',
            'min_len' => 7,
            'max_len' => 10,
            'hint' => '7-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+375',
            'name' => 'Belarus',
            'flag' => '🇧🇾',
            'iso' => 'BY',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+32',
            'name' => 'Belgium',
            'flag' => '🇧🇪',
            'iso' => 'BE',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+501',
            'name' => 'Belize',
            'flag' => '🇧🇿',
            'iso' => 'BZ',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+229',
            'name' => 'Benin',
            'flag' => '🇧🇯',
            'iso' => 'BJ',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+975',
            'name' => 'Bhutan',
            'flag' => '🇧🇹',
            'iso' => 'BT',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+591',
            'name' => 'Bolivia',
            'flag' => '🇧🇴',
            'iso' => 'BO',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+387',
            'name' => 'Bosnia and Herzegovina',
            'flag' => '🇧🇦',
            'iso' => 'BA',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+267',
            'name' => 'Botswana',
            'flag' => '🇧🇼',
            'iso' => 'BW',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+55',
            'name' => 'Brazil',
            'flag' => '🇧🇷',
            'iso' => 'BR',
            'min_len' => 10,
            'max_len' => 11,
            'hint' => '10-11 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+673',
            'name' => 'Brunei',
            'flag' => '🇧🇳',
            'iso' => 'BN',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+359',
            'name' => 'Bulgaria',
            'flag' => '🇧🇬',
            'iso' => 'BG',
            'min_len' => 8,
            'max_len' => 9,
            'hint' => '8-9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+226',
            'name' => 'Burkina Faso',
            'flag' => '🇧🇫',
            'iso' => 'BF',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+257',
            'name' => 'Burundi',
            'flag' => '🇧🇮',
            'iso' => 'BI',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+855',
            'name' => 'Cambodia',
            'flag' => '🇰🇭',
            'iso' => 'KH',
            'min_len' => 8,
            'max_len' => 9,
            'hint' => '8-9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+237',
            'name' => 'Cameroon',
            'flag' => '🇨🇲',
            'iso' => 'CM',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1',
            'name' => 'Canada',
            'flag' => '🇨🇦',
            'iso' => 'CA',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+238',
            'name' => 'Cape Verde',
            'flag' => '🇨🇻',
            'iso' => 'CV',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+236',
            'name' => 'Central African Republic',
            'flag' => '🇨🇫',
            'iso' => 'CF',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+235',
            'name' => 'Chad',
            'flag' => '🇹🇩',
            'iso' => 'TD',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+56',
            'name' => 'Chile',
            'flag' => '🇨🇱',
            'iso' => 'CL',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+86',
            'name' => 'China',
            'flag' => '🇨🇳',
            'iso' => 'CN',
            'min_len' => 11,
            'max_len' => 11,
            'hint' => '11 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+57',
            'name' => 'Colombia',
            'flag' => '🇨🇴',
            'iso' => 'CO',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+269',
            'name' => 'Comoros',
            'flag' => '🇰🇲',
            'iso' => 'KM',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+242',
            'name' => 'Congo',
            'flag' => '🇨🇬',
            'iso' => 'CG',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+243',
            'name' => 'Congo (DRC)',
            'flag' => '🇨🇩',
            'iso' => 'CD',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+506',
            'name' => 'Costa Rica',
            'flag' => '🇨🇷',
            'iso' => 'CR',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+385',
            'name' => 'Croatia',
            'flag' => '🇭🇷',
            'iso' => 'HR',
            'min_len' => 8,
            'max_len' => 9,
            'hint' => '8-9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+53',
            'name' => 'Cuba',
            'flag' => '🇨🇺',
            'iso' => 'CU',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+357',
            'name' => 'Cyprus',
            'flag' => '🇨🇾',
            'iso' => 'CY',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+420',
            'name' => 'Czech Republic',
            'flag' => '🇨🇿',
            'iso' => 'CZ',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+45',
            'name' => 'Denmark',
            'flag' => '🇩🇰',
            'iso' => 'DK',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+253',
            'name' => 'Djibouti',
            'flag' => '🇩🇯',
            'iso' => 'DJ',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1767',
            'name' => 'Dominica',
            'flag' => '🇩🇲',
            'iso' => 'DM',
            'min_len' => 7,
            'max_len' => 10,
            'hint' => '7-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1809',
            'name' => 'Dominican Republic',
            'flag' => '🇩🇴',
            'iso' => 'DO',
            'min_len' => 7,
            'max_len' => 10,
            'hint' => '7-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+593',
            'name' => 'Ecuador',
            'flag' => '🇪🇨',
            'iso' => 'EC',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+20',
            'name' => 'Egypt',
            'flag' => '🇪🇬',
            'iso' => 'EG',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+503',
            'name' => 'El Salvador',
            'flag' => '🇸🇻',
            'iso' => 'SV',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+240',
            'name' => 'Equatorial Guinea',
            'flag' => '🇬🇶',
            'iso' => 'GQ',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+291',
            'name' => 'Eritrea',
            'flag' => '🇪🇷',
            'iso' => 'ER',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+372',
            'name' => 'Estonia',
            'flag' => '🇪🇪',
            'iso' => 'EE',
            'min_len' => 7,
            'max_len' => 8,
            'hint' => '7-8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+268',
            'name' => 'Eswatini',
            'flag' => '🇸🇿',
            'iso' => 'SZ',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+251',
            'name' => 'Ethiopia',
            'flag' => '🇪🇹',
            'iso' => 'ET',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+679',
            'name' => 'Fiji',
            'flag' => '🇫🇯',
            'iso' => 'FJ',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+358',
            'name' => 'Finland',
            'flag' => '🇫🇮',
            'iso' => 'FI',
            'min_len' => 9,
            'max_len' => 10,
            'hint' => '9-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+33',
            'name' => 'France',
            'flag' => '🇫🇷',
            'iso' => 'FR',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+241',
            'name' => 'Gabon',
            'flag' => '🇬🇦',
            'iso' => 'GA',
            'min_len' => 7,
            'max_len' => 8,
            'hint' => '7-8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+220',
            'name' => 'Gambia',
            'flag' => '🇬🇲',
            'iso' => 'GM',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+995',
            'name' => 'Georgia',
            'flag' => '🇬🇪',
            'iso' => 'GE',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+49',
            'name' => 'Germany',
            'flag' => '🇩🇪',
            'iso' => 'DE',
            'min_len' => 10,
            'max_len' => 11,
            'hint' => '10-11 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+233',
            'name' => 'Ghana',
            'flag' => '🇬🇭',
            'iso' => 'GH',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+30',
            'name' => 'Greece',
            'flag' => '🇬🇷',
            'iso' => 'GR',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1473',
            'name' => 'Grenada',
            'flag' => '🇬🇩',
            'iso' => 'GD',
            'min_len' => 7,
            'max_len' => 10,
            'hint' => '7-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+502',
            'name' => 'Guatemala',
            'flag' => '🇬🇹',
            'iso' => 'GT',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+224',
            'name' => 'Guinea',
            'flag' => '🇬🇳',
            'iso' => 'GN',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+245',
            'name' => 'Guinea-Bissau',
            'flag' => '🇬🇼',
            'iso' => 'GW',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+592',
            'name' => 'Guyana',
            'flag' => '🇬🇾',
            'iso' => 'GY',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+509',
            'name' => 'Haiti',
            'flag' => '🇭🇹',
            'iso' => 'HT',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+504',
            'name' => 'Honduras',
            'flag' => '🇭🇳',
            'iso' => 'HN',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+852',
            'name' => 'Hong Kong',
            'flag' => '🇭🇰',
            'iso' => 'HK',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+36',
            'name' => 'Hungary',
            'flag' => '🇭🇺',
            'iso' => 'HU',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+354',
            'name' => 'Iceland',
            'flag' => '🇮🇸',
            'iso' => 'IS',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+62',
            'name' => 'Indonesia',
            'flag' => '🇮🇩',
            'iso' => 'ID',
            'min_len' => 9,
            'max_len' => 12,
            'hint' => '9-12 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+98',
            'name' => 'Iran',
            'flag' => '🇮🇷',
            'iso' => 'IR',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+964',
            'name' => 'Iraq',
            'flag' => '🇮🇶',
            'iso' => 'IQ',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+353',
            'name' => 'Ireland',
            'flag' => '🇮🇪',
            'iso' => 'IE',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+39',
            'name' => 'Italy',
            'flag' => '🇮🇹',
            'iso' => 'IT',
            'min_len' => 9,
            'max_len' => 10,
            'hint' => '9-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+225',
            'name' => 'Ivory Coast',
            'flag' => '🇨🇮',
            'iso' => 'CI',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1876',
            'name' => 'Jamaica',
            'flag' => '🇯🇲',
            'iso' => 'JM',
            'min_len' => 7,
            'max_len' => 10,
            'hint' => '7-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+81',
            'name' => 'Japan',
            'flag' => '🇯🇵',
            'iso' => 'JP',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+962',
            'name' => 'Jordan',
            'flag' => '🇯🇴',
            'iso' => 'JO',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+7',
            'name' => 'Kazakhstan',
            'flag' => '🇰🇿',
            'iso' => 'KZ',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+254',
            'name' => 'Kenya',
            'flag' => '🇰🇪',
            'iso' => 'KE',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+383',
            'name' => 'Kosovo',
            'flag' => '🇽🇰',
            'iso' => 'XK',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+996',
            'name' => 'Kyrgyzstan',
            'flag' => '🇰🇬',
            'iso' => 'KG',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+856',
            'name' => 'Laos',
            'flag' => '🇱🇦',
            'iso' => 'LA',
            'min_len' => 8,
            'max_len' => 10,
            'hint' => '8-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+371',
            'name' => 'Latvia',
            'flag' => '🇱🇻',
            'iso' => 'LV',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+961',
            'name' => 'Lebanon',
            'flag' => '🇱🇧',
            'iso' => 'LB',
            'min_len' => 7,
            'max_len' => 8,
            'hint' => '7-8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+266',
            'name' => 'Lesotho',
            'flag' => '🇱🇸',
            'iso' => 'LS',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+231',
            'name' => 'Liberia',
            'flag' => '🇱🇷',
            'iso' => 'LR',
            'min_len' => 7,
            'max_len' => 8,
            'hint' => '7-8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+218',
            'name' => 'Libya',
            'flag' => '🇱🇾',
            'iso' => 'LY',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+423',
            'name' => 'Liechtenstein',
            'flag' => '🇱🇮',
            'iso' => 'LI',
            'min_len' => 7,
            'max_len' => 9,
            'hint' => '7-9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+370',
            'name' => 'Lithuania',
            'flag' => '🇱🇹',
            'iso' => 'LT',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+352',
            'name' => 'Luxembourg',
            'flag' => '🇱🇺',
            'iso' => 'LU',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+853',
            'name' => 'Macau',
            'flag' => '🇲🇴',
            'iso' => 'MO',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+261',
            'name' => 'Madagascar',
            'flag' => '🇲🇬',
            'iso' => 'MG',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+265',
            'name' => 'Malawi',
            'flag' => '🇲🇼',
            'iso' => 'MW',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+60',
            'name' => 'Malaysia',
            'flag' => '🇲🇾',
            'iso' => 'MY',
            'min_len' => 9,
            'max_len' => 10,
            'hint' => '9-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+960',
            'name' => 'Maldives',
            'flag' => '🇲🇻',
            'iso' => 'MV',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+223',
            'name' => 'Mali',
            'flag' => '🇲🇱',
            'iso' => 'ML',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+356',
            'name' => 'Malta',
            'flag' => '🇲🇹',
            'iso' => 'MT',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+222',
            'name' => 'Mauritania',
            'flag' => '🇲🇷',
            'iso' => 'MR',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+230',
            'name' => 'Mauritius',
            'flag' => '🇲🇺',
            'iso' => 'MU',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+52',
            'name' => 'Mexico',
            'flag' => '🇲🇽',
            'iso' => 'MX',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+373',
            'name' => 'Moldova',
            'flag' => '🇲🇩',
            'iso' => 'MD',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+377',
            'name' => 'Monaco',
            'flag' => '🇲🇨',
            'iso' => 'MC',
            'min_len' => 8,
            'max_len' => 9,
            'hint' => '8-9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+976',
            'name' => 'Mongolia',
            'flag' => '🇲🇳',
            'iso' => 'MN',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+382',
            'name' => 'Montenegro',
            'flag' => '🇲🇪',
            'iso' => 'ME',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+212',
            'name' => 'Morocco',
            'flag' => '🇲🇦',
            'iso' => 'MA',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+258',
            'name' => 'Mozambique',
            'flag' => '🇲🇿',
            'iso' => 'MZ',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+95',
            'name' => 'Myanmar',
            'flag' => '🇲🇲',
            'iso' => 'MM',
            'min_len' => 8,
            'max_len' => 10,
            'hint' => '8-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+264',
            'name' => 'Namibia',
            'flag' => '🇳🇦',
            'iso' => 'NA',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+977',
            'name' => 'Nepal',
            'flag' => '🇳🇵',
            'iso' => 'NP',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+31',
            'name' => 'Netherlands',
            'flag' => '🇳🇱',
            'iso' => 'NL',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+64',
            'name' => 'New Zealand',
            'flag' => '🇳🇿',
            'iso' => 'NZ',
            'min_len' => 8,
            'max_len' => 10,
            'hint' => '8-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+505',
            'name' => 'Nicaragua',
            'flag' => '🇳🇮',
            'iso' => 'NI',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+227',
            'name' => 'Niger',
            'flag' => '🇳🇪',
            'iso' => 'NE',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+234',
            'name' => 'Nigeria',
            'flag' => '🇳🇬',
            'iso' => 'NG',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+389',
            'name' => 'North Macedonia',
            'flag' => '🇲🇰',
            'iso' => 'MK',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+47',
            'name' => 'Norway',
            'flag' => '🇳🇴',
            'iso' => 'NO',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+92',
            'name' => 'Pakistan',
            'flag' => '🇵🇰',
            'iso' => 'PK',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+970',
            'name' => 'Palestine',
            'flag' => '🇵🇸',
            'iso' => 'PS',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+507',
            'name' => 'Panama',
            'flag' => '🇵🇦',
            'iso' => 'PA',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+675',
            'name' => 'Papua New Guinea',
            'flag' => '🇵🇬',
            'iso' => 'PG',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+595',
            'name' => 'Paraguay',
            'flag' => '🇵🇾',
            'iso' => 'PY',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+51',
            'name' => 'Peru',
            'flag' => '🇵🇪',
            'iso' => 'PE',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+63',
            'name' => 'Philippines',
            'flag' => '🇵🇭',
            'iso' => 'PH',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+48',
            'name' => 'Poland',
            'flag' => '🇵🇱',
            'iso' => 'PL',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+351',
            'name' => 'Portugal',
            'flag' => '🇵🇹',
            'iso' => 'PT',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+40',
            'name' => 'Romania',
            'flag' => '🇷🇴',
            'iso' => 'RO',
            'min_len' => 9,
            'max_len' => 10,
            'hint' => '9-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+7',
            'name' => 'Russia',
            'flag' => '🇷🇺',
            'iso' => 'RU',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+250',
            'name' => 'Rwanda',
            'flag' => '🇷🇼',
            'iso' => 'RW',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+221',
            'name' => 'Senegal',
            'flag' => '🇸🇳',
            'iso' => 'SN',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+381',
            'name' => 'Serbia',
            'flag' => '🇷🇸',
            'iso' => 'RS',
            'min_len' => 8,
            'max_len' => 9,
            'hint' => '8-9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+248',
            'name' => 'Seychelles',
            'flag' => '🇸🇨',
            'iso' => 'SC',
            'min_len' => 7,
            'max_len' => 7,
            'hint' => '7 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+232',
            'name' => 'Sierra Leone',
            'flag' => '🇸🇱',
            'iso' => 'SL',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+65',
            'name' => 'Singapore',
            'flag' => '🇸🇬',
            'iso' => 'SG',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+421',
            'name' => 'Slovakia',
            'flag' => '🇸🇰',
            'iso' => 'SK',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+386',
            'name' => 'Slovenia',
            'flag' => '🇸🇮',
            'iso' => 'SI',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+252',
            'name' => 'Somalia',
            'flag' => '🇸🇴',
            'iso' => 'SO',
            'min_len' => 8,
            'max_len' => 9,
            'hint' => '8-9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+27',
            'name' => 'South Africa',
            'flag' => '🇿🇦',
            'iso' => 'ZA',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+82',
            'name' => 'South Korea',
            'flag' => '🇰🇷',
            'iso' => 'KR',
            'min_len' => 9,
            'max_len' => 10,
            'hint' => '9-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+211',
            'name' => 'South Sudan',
            'flag' => '🇸🇸',
            'iso' => 'SS',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+34',
            'name' => 'Spain',
            'flag' => '🇪🇸',
            'iso' => 'ES',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+94',
            'name' => 'Sri Lanka',
            'flag' => '🇱🇰',
            'iso' => 'LK',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+249',
            'name' => 'Sudan',
            'flag' => '🇸🇩',
            'iso' => 'SD',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+46',
            'name' => 'Sweden',
            'flag' => '🇸🇪',
            'iso' => 'SE',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+41',
            'name' => 'Switzerland',
            'flag' => '🇨🇭',
            'iso' => 'CH',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+963',
            'name' => 'Syria',
            'flag' => '🇸🇾',
            'iso' => 'SY',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+886',
            'name' => 'Taiwan',
            'flag' => '🇹🇼',
            'iso' => 'TW',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+992',
            'name' => 'Tajikistan',
            'flag' => '🇹🇯',
            'iso' => 'TJ',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+255',
            'name' => 'Tanzania',
            'flag' => '🇹🇿',
            'iso' => 'TZ',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+66',
            'name' => 'Thailand',
            'flag' => '🇹🇭',
            'iso' => 'TH',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+228',
            'name' => 'Togo',
            'flag' => '🇹🇬',
            'iso' => 'TG',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1868',
            'name' => 'Trinidad and Tobago',
            'flag' => '🇹🇹',
            'iso' => 'TT',
            'min_len' => 7,
            'max_len' => 10,
            'hint' => '7-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+216',
            'name' => 'Tunisia',
            'flag' => '🇹🇳',
            'iso' => 'TN',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+90',
            'name' => 'Turkey',
            'flag' => '🇹🇷',
            'iso' => 'TR',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+993',
            'name' => 'Turkmenistan',
            'flag' => '🇹🇲',
            'iso' => 'TM',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+256',
            'name' => 'Uganda',
            'flag' => '🇺🇬',
            'iso' => 'UG',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+380',
            'name' => 'Ukraine',
            'flag' => '🇺🇦',
            'iso' => 'UA',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+44',
            'name' => 'United Kingdom',
            'flag' => '🇬🇧',
            'iso' => 'GB',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+1',
            'name' => 'United States',
            'flag' => '🇺🇸',
            'iso' => 'US',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+598',
            'name' => 'Uruguay',
            'flag' => '🇺🇾',
            'iso' => 'UY',
            'min_len' => 8,
            'max_len' => 8,
            'hint' => '8 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+998',
            'name' => 'Uzbekistan',
            'flag' => '🇺🇿',
            'iso' => 'UZ',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+58',
            'name' => 'Venezuela',
            'flag' => '🇻🇪',
            'iso' => 'VE',
            'min_len' => 10,
            'max_len' => 10,
            'hint' => '10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+84',
            'name' => 'Vietnam',
            'flag' => '🇻🇳',
            'iso' => 'VN',
            'min_len' => 9,
            'max_len' => 10,
            'hint' => '9-10 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+967',
            'name' => 'Yemen',
            'flag' => '🇾🇪',
            'iso' => 'YE',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+260',
            'name' => 'Zambia',
            'flag' => '🇿🇲',
            'iso' => 'ZM',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
        ],
        [
            'code' => '+263',
            'name' => 'Zimbabwe',
            'flag' => '🇿🇼',
            'iso' => 'ZW',
            'min_len' => 9,
            'max_len' => 9,
            'hint' => '9 digits',
            'group' => 'Other',
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
            if (!isset($options[$c['code']])) {
                $options[$c['code']] = "{$c['flag']} {$c['code']} ({$c['name']})";
            }
        }
        return $options;
    }

    /**
     * Get exact candidate telephone number formats for safe, indexed SQL lookups.
     * Guaranteed to return only exact candidate strings with >= 7 digits (no wildcards).
     *
     * @return array<string>
     */
    public static function getPhoneCandidates(string $phone, ?string $countryCode = null): array
    {
        $norm = self::normalize($phone, $countryCode);
        $full = $norm['full_phone'];
        $fullWithoutPlus = ltrim($full, '+');
        $local = $norm['local_number'];
        $raw = $norm['raw_digits'];
        $code = $norm['country_code'];

        $candidates = [
            $full,
            $fullWithoutPlus,
        ];

        if (!empty($raw)) {
            $candidates[] = $raw;
            $candidates[] = '+' . $raw;
        }

        if (!empty($local)) {
            $candidates[] = $local;
            $candidates[] = $code . $local;
            $candidates[] = ltrim($code, '+') . $local;
            if ($code === '+91') {
                $candidates[] = '+91' . $local;
                $candidates[] = '91' . $local;
            }
        }

        // Filter: only strings with at least 7 digits to prevent wildcard/partial collisions
        $filtered = array_filter(array_unique($candidates), function ($cand) {
            $digits = preg_replace('/\D/', '', (string) $cand);
            return strlen($digits) >= 7;
        });

        return array_values($filtered);
    }

    /**
     * Securely find an existing User by phone number using strict equality matching.
     * Prevents SQL wildcard injection and partial number collisions completely.
     */
    public static function findUser(string $phone, ?string $countryCode = null): ?User
    {
        $candidates = self::getPhoneCandidates($phone, $countryCode);
        if (empty($candidates)) {
            return null;
        }

        $norm = self::normalize($phone, $countryCode);
        $code = $norm['country_code'];
        $local = $norm['local_number'];

        return User::where(function ($q) use ($candidates, $code, $local) {
            $q->whereIn('whatsapp_number', $candidates);
            if (!empty($code) && !empty($local) && strlen($local) >= 7) {
                $q->orWhere(function ($sub) use ($code, $local) {
                    $sub->where('country_code', $code)
                        ->where('whatsapp_number', $local);
                });
            }
        })->first();
    }
}
