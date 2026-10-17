// Participant data type
export interface Participant {
  id: number;
  name: string;
  countryCode: string;
  countryCallingCode?: string;
  place: string;
  mobile: string;
  locality?: string;
  territory?: string;
}

// Application state types
export type DrawStatus =
  | 'ready'
  | 'selecting'
  | 'winner-selected'
  | 'confirm-publish'
  | 'publishing'
  | 'published';

export interface DrawState {
  status: DrawStatus;
  winner: Participant | null;
  winnersCount: number;
  isPublished: boolean;
}

export interface WeekInfo {
  id: number;
  week_number: number;
  quiz_date?: string;
  status?: string;
  lucky_draw_status?: 'pending' | 'drawn' | 'published';
  eligible_count?: number;
  winner?: {
    id: number;
    name: string;
    place?: string;
    country_code?: string;
    whatsapp_number?: string;
    locality?: string;
    territory?: string;
  } | null;
}

// Status badge label mapping
export const STATUS_LABELS: Record<DrawStatus, string> = {
  ready: 'READY',
  selecting: 'SELECTING',
  'winner-selected': 'WINNER SELECTED',
  'confirm-publish': 'WINNER SELECTED',
  publishing: 'PUBLISHING',
  published: 'PUBLISHED',
};

/**
 * Maps calling codes or country codes (+91, +968, +971, IN, OM, etc.) to 2-letter ISO country code.
 */
export function callingCodeToIso(code?: string): string {
  if (!code) return 'OM';
  const clean = code.trim().toUpperCase();
  if (/^[A-Z]{2}$/.test(clean)) return clean;

  const digits = clean.replace(/\D/g, '');
  const prefixMap: Record<string, string> = {
    '968': 'OM', // Oman
    '971': 'AE', // UAE
    '966': 'SA', // Saudi Arabia
    '974': 'QA', // Qatar
    '965': 'KW', // Kuwait
    '973': 'BH', // Bahrain
    '91': 'IN',  // India
    '44': 'GB',  // UK
    '1': 'US',   // USA / Canada
    '46': 'SE',  // Sweden
    '47': 'NO',  // Norway
    '60': 'MY',  // Malaysia
    '65': 'SG',  // Singapore
    '20': 'EG',  // Egypt
    '92': 'PK',  // Pakistan
    '880': 'BD', // Bangladesh
    '90': 'TR',  // Turkey
    '33': 'FR',  // France
    '49': 'DE',  // Germany
    '39': 'IT',  // Italy
    '34': 'ES',  // Spain
    '55': 'BR',  // Brazil
    '233': 'GH', // Ghana
    '998': 'UZ', // Uzbekistan
    '81': 'JP',  // Japan
    '32': 'BE',  // Belgium
    '48': 'PL',  // Poland
    '353': 'IE', // Ireland
    '52': 'MX',  // Mexico
    '61': 'AU',  // Australia
    '54': 'AR',  // Argentina
    '86': 'CN',  // China
    '27': 'ZA',  // South Africa
    '359': 'BG', // Bulgaria
    '57': 'CO',  // Colombia
    '234': 'NG', // Nigeria
    '40': 'RO',  // Romania
    '351': 'PT', // Portugal
    '56': 'CL',  // Chile
    '221': 'SN', // Senegal
  };

  return prefixMap[digits] || 'OM';
}

/**
 * Reusable formatting function for mobile numbers.
 * Masks the first six digits with asterisks (*) and shows only the remaining digits.
 * Preserves the country calling prefix (+968, +91, etc.) when explicitly stored or present.
 */
export function maskMobileNumber(mobile: string, countryCallingCode?: string): string {
  if (!mobile) return '';
  const trimmed = mobile.trim();

  // If a separate country calling code is explicitly provided
  if (countryCallingCode && countryCallingCode.trim()) {
    const prefix = countryCallingCode.trim();
    const formattedPrefix = prefix.startsWith('+') ? prefix : `+${prefix}`;
    const cleanPrefixDigits = formattedPrefix.replace(/\D/g, '');

    // Extract subscriber number by removing prefix if already present in mobile
    let subscriber = trimmed;
    if (subscriber.startsWith(formattedPrefix)) {
      subscriber = subscriber.slice(formattedPrefix.length).trim();
    } else if (cleanPrefixDigits && subscriber.replace(/^\+/, '').startsWith(cleanPrefixDigits)) {
      subscriber = subscriber.replace(/^\+/, '').slice(cleanPrefixDigits.length).trim();
    }

    let digitCount = 0;
    let maskedSub = '';
    for (let i = 0; i < subscriber.length; i++) {
      const ch = subscriber[i];
      if (/\d/.test(ch)) {
        if (digitCount < 6) {
          maskedSub += '*';
          digitCount++;
        } else {
          maskedSub += ch;
        }
      } else {
        maskedSub += ch;
      }
    }

    return `${formattedPrefix} ${maskedSub}`;
  }

  // If no separate country calling code
  const startsWithPlus = trimmed.startsWith('+');
  const core = startsWithPlus ? trimmed.slice(1) : trimmed;

  let digitCount = 0;
  let masked = '';
  for (let i = 0; i < core.length; i++) {
    const ch = core[i];
    if (/\d/.test(ch)) {
      if (digitCount < 6) {
        masked += '*';
        digitCount++;
      } else {
        masked += ch;
      }
    } else {
      masked += ch;
    }
  }

  return (startsWithPlus ? '+' : '') + masked;
}

/**
 * Converts a 2-letter ISO country code (e.g. 'SE', 'OM', 'AE') into a unicode country flag emoji.
 */
export function getCountryFlag(countryCode: string): string {
  if (!countryCode) return '🏳️';
  const clean = countryCode.trim().toUpperCase();
  if (clean.length !== 2 || !/^[A-Z]{2}$/.test(clean)) {
    const iso = callingCodeToIso(clean);
    if (iso && iso.length === 2) {
      const codePoints = iso.split('').map(char => 0x1f1e6 + char.charCodeAt(0) - 65);
      return String.fromCodePoint(...codePoints);
    }
    return '🏳️';
  }
  const codePoints = clean
    .split('')
    .map(char => 0x1f1e6 + char.charCodeAt(0) - 65);
  return String.fromCodePoint(...codePoints);
}

/**
 * Gets human-readable country name from ISO country code.
 */
export function getCountryName(countryCode: string): string {
  if (!countryCode) return '';
  const clean = countryCode.trim().toUpperCase();
  const iso = clean.length === 2 && /^[A-Z]{2}$/.test(clean) ? clean : callingCodeToIso(clean);
  try {
    const regionNames = new Intl.DisplayNames(['en'], { type: 'region' });
    return regionNames.of(iso) || iso;
  } catch {
    return iso;
  }
}

/**
 * Formats the participant's location string combining country name and place.
 * E.g. "Sweden · Stockholm" or "Oman · Muscat"
 */
export function formatParticipantLocation(participant: { countryCode: string; place: string }): string {
  const country = getCountryName(participant.countryCode);
  if (!participant.place) return country;
  if (country && participant.place.toLowerCase().includes(country.toLowerCase())) {
    return participant.place;
  }
  return country ? `${country} · ${participant.place}` : participant.place;
}

// Sample participants – realistic entries with shared names across different countries/places
export const SAMPLE_PARTICIPANTS: Participant[] = [
  {
    id: 15,
    name: 'Olivia Svensson',
    countryCode: 'SE',
    countryCallingCode: '+46',
    place: 'Stockholm',
    mobile: '7012341234',
  },
  {
    id: 36,
    name: 'Olivia Svensson',
    countryCode: 'GB',
    countryCallingCode: '+44',
    place: 'London',
    mobile: '7911123456',
  },
  {
    id: 37,
    name: 'Olivia Svensson',
    countryCode: 'NO',
    countryCallingCode: '+47',
    place: 'Oslo',
    mobile: '91234567',
  },
  {
    id: 32,
    name: 'Fatima Al-Zahra',
    countryCode: 'OM',
    countryCallingCode: '+968',
    place: 'Muscat',
    mobile: '98765432',
  },
  {
    id: 38,
    name: 'Fatima Al-Zahra',
    countryCode: 'AE',
    countryCallingCode: '+971',
    place: 'Dubai',
    mobile: '501234567',
  },
  {
    id: 39,
    name: 'Fatima Al-Zahra',
    countryCode: 'SA',
    countryCallingCode: '+966',
    place: 'Riyadh',
    mobile: '551234567',
  },
  {
    id: 40,
    name: 'Mohammed Al-Balushi',
    countryCode: 'OM',
    countryCallingCode: '+968',
    place: 'Sohar',
    mobile: '91234567',
  },
  {
    id: 41,
    name: 'Mohammed Al-Balushi',
    countryCode: 'OM',
    countryCallingCode: '+968',
    place: 'Salalah',
    mobile: '95554321',
  },
  {
    id: 26,
    name: 'Zara Patel',
    countryCode: 'IN',
    countryCallingCode: '+91',
    place: 'Mumbai',
    mobile: '9876543210',
  },
  {
    id: 42,
    name: 'Zara Patel',
    countryCode: 'GB',
    countryCallingCode: '+44',
    place: 'Birmingham',
    mobile: '7700900123',
  },
  {
    id: 3,
    name: 'Camille Rousseau',
    countryCode: 'FR',
    countryCallingCode: '+33',
    place: 'Paris',
    mobile: '612345678',
  },
  {
    id: 43,
    name: 'Camille Rousseau',
    countryCode: 'CA',
    countryCallingCode: '+1',
    place: 'Montreal',
    mobile: '5141234567',
  },
  {
    id: 1,
    name: 'Alexandra Thornton',
    countryCode: 'GB',
    countryCallingCode: '+44',
    place: 'Edinburgh',
    mobile: '7700900456',
  },
  {
    id: 2,
    name: 'Benjamin Osei',
    countryCode: 'GH',
    countryCallingCode: '+233',
    place: 'Accra',
    mobile: '241234567',
  },
  {
    id: 4,
    name: 'Daniel Ferreira',
    countryCode: 'BR',
    countryCallingCode: '+55',
    place: 'São Paulo',
    mobile: '11987654321',
  },
  {
    id: 5,
    name: 'Elena Vasquez',
    countryCode: 'ES',
    countryCallingCode: '+34',
    place: 'Madrid',
    mobile: '612345678',
  },
  {
    id: 6,
    name: 'Farrukh Tashmatov',
    countryCode: 'UZ',
    countryCallingCode: '+998',
    place: 'Tashkent',
    mobile: '901234567',
  },
  {
    id: 7,
    name: 'Grace Nakamura',
    countryCode: 'JP',
    countryCallingCode: '+81',
    place: 'Kyoto',
    mobile: '9012345678',
  },
  {
    id: 8,
    name: 'Hamid Al-Rashid',
    countryCode: 'OM',
    countryCallingCode: '+968',
    place: 'Nizwa',
    mobile: '92345678',
  },
  {
    id: 9,
    name: 'Isabelle Dupont',
    countryCode: 'BE',
    countryCallingCode: '+32',
    place: 'Brussels',
    mobile: '470123456',
  },
  {
    id: 10,
    name: 'James Kowalski',
    countryCode: 'PL',
    countryCallingCode: '+48',
    place: 'Warsaw',
    mobile: '501234567',
  },
  {
    id: 11,
    name: 'Keiko Tanaka',
    countryCode: 'JP',
    countryCallingCode: '+81',
    place: 'Tokyo',
    mobile: '8012345678',
  },
  {
    id: 12,
    name: "Liam O'Sullivan",
    countryCode: 'IE',
    countryCallingCode: '+353',
    place: 'Dublin',
    mobile: '871234567',
  },
  {
    id: 13,
    name: 'Maria Gonzalez',
    countryCode: 'MX',
    countryCallingCode: '+52',
    place: 'Monterrey',
    mobile: '8112345678',
  },
  {
    id: 14,
    name: 'Nathan Blackwood',
    countryCode: 'AU',
    countryCallingCode: '+61',
    place: 'Melbourne',
    mobile: '412345678',
  },
  {
    id: 16,
    name: 'Pablo Morales',
    countryCode: 'AR',
    countryCallingCode: '+54',
    place: 'Buenos Aires',
    mobile: '1123456789',
  },
  {
    id: 17,
    name: 'Qiang Liu',
    countryCode: 'CN',
    countryCallingCode: '+86',
    place: 'Shanghai',
    mobile: '13812345678',
  },
  {
    id: 18,
    name: 'Rania Hassan',
    countryCode: 'EG',
    countryCallingCode: '+20',
    place: 'Alexandria',
    mobile: '1012345678',
  },
  {
    id: 19,
    name: 'Sebastian Müller',
    countryCode: 'DE',
    countryCallingCode: '+49',
    place: 'Munich',
    mobile: '15112345678',
  },
  {
    id: 20,
    name: 'Thembeka Dlamini',
    countryCode: 'ZA',
    countryCallingCode: '+27',
    place: 'Cape Town',
    mobile: '821234567',
  },
  {
    id: 21,
    name: 'Umar Khalid',
    countryCode: 'PK',
    countryCallingCode: '+92',
    place: 'Lahore',
    mobile: '3001234567',
  },
  {
    id: 22,
    name: 'Victoria Petrov',
    countryCode: 'BG',
    countryCallingCode: '+359',
    place: 'Sofia',
    mobile: '881234567',
  },
  {
    id: 23,
    name: 'William Chen',
    countryCode: 'SG',
    countryCallingCode: '+65',
    place: 'Singapore',
    mobile: '91234567',
  },
  {
    id: 24,
    name: 'Xiomara Rivera',
    countryCode: 'CO',
    countryCallingCode: '+57',
    place: 'Bogotá',
    mobile: '3001234567',
  },
  {
    id: 25,
    name: 'Yusuf Abubakar',
    countryCode: 'NG',
    countryCallingCode: '+234',
    place: 'Kano',
    mobile: '8021234567',
  },
  {
    id: 27,
    name: 'Andrei Popescu',
    countryCode: 'RO',
    countryCallingCode: '+40',
    place: 'Bucharest',
    mobile: '721234567',
  },
  {
    id: 28,
    name: 'Beatriz Carvalho',
    countryCode: 'PT',
    countryCallingCode: '+351',
    place: 'Lisbon',
    mobile: '911234567',
  },
  {
    id: 29,
    name: 'Chioma Okafor',
    countryCode: 'NG',
    countryCallingCode: '+234',
    place: 'Lagos',
    mobile: '8031234567',
  },
  {
    id: 30,
    name: 'Diego Santana',
    countryCode: 'CL',
    countryCallingCode: '+56',
    place: 'Santiago',
    mobile: '912345678',
  },
  {
    id: 31,
    name: 'Emre Yilmaz',
    countryCode: 'TR',
    countryCallingCode: '+90',
    place: 'Istanbul',
    mobile: '5321234567',
  },
  {
    id: 33,
    name: 'Giovanni Ricci',
    countryCode: 'IT',
    countryCallingCode: '+39',
    place: 'Florence',
    mobile: '3401234567',
  },
  {
    id: 34,
    name: 'Hannah Bergström',
    countryCode: 'SE',
    countryCallingCode: '+46',
    place: 'Gothenburg',
    mobile: '709876543',
  },
  {
    id: 35,
    name: 'Ibrahim Diallo',
    countryCode: 'SN',
    countryCallingCode: '+221',
    place: 'Dakar',
    mobile: '771234567',
  },
];
