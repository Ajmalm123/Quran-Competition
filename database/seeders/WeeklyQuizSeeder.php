<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\LuckyDraw;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use App\Models\User;
use App\Services\QuizService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WeeklyQuizSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Super Admin exists
        Admin::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('admin123'),
                'status' => 'active',
            ]
        );

        // 2. Create 10 Quiz Weeks (Week 1 is Live, Weeks 2-10 are Upcoming on consecutive Fridays at 8:00 PM)
        $now = Carbon::now();
        $service = app(QuizService::class);

        // Week 1: Live now (Started 2 hours ago, ends in 22 hours)
        $week1 = QuizWeek::updateOrCreate(
            ['week_number' => 1],
            [
                'quiz_date' => $now->toDateString(),
                'start_at' => $now->copy()->subHours(2),
                'end_at' => $now->copy()->addHours(22),
                'status' => QuizWeek::STATUS_LIVE,
            ]
        );

        // Upcoming Weeks (Every following Friday at 8:00 PM IST)
        $nextFriday = $now->copy()->next(Carbon::FRIDAY)->setTime(20, 0, 0);
        if ($nextFriday->isPast()) {
            $nextFriday->addWeek();
        }

        for ($w = 2; $w <= 10; $w++) {
            $start = $nextFriday->copy()->addWeeks($w - 2);
            $end = $start->copy()->addHours(2); // 8:00 PM to 10:00 PM

            QuizWeek::updateOrCreate(
                ['week_number' => $w],
                [
                    'quiz_date' => $start->toDateString(),
                    'start_at' => $start,
                    'end_at' => $end,
                    'status' => QuizWeek::STATUS_UPCOMING,
                ]
            );
        }

        // 3. Create 6 Questions for Week 1
        $questionsData = [
            [
                'question_text' => 'In which Surah is Ayat al-Kursi located?',
                'option_a' => 'Surah Al-Baqarah',
                'option_b' => 'Surah Ali Imran',
                'option_c' => 'Surah An-Nisa',
                'option_d' => 'Surah Al-Ma\'idah',
                'correct_option' => 'A',
                'sort_order' => 1,
            ],
            [
                'question_text' => 'How many Surahs are in the Holy Quran?',
                'option_a' => '110',
                'option_b' => '112',
                'option_c' => '114',
                'option_d' => '116',
                'correct_option' => 'C',
                'sort_order' => 2,
            ],
            [
                'question_text' => 'Which is the shortest Surah in the Holy Quran?',
                'option_a' => 'Surah Al-Ikhlas',
                'option_b' => 'Surah Al-Kawthar',
                'option_c' => 'Surah An-Nasr',
                'option_d' => 'Surah Al-Asr',
                'correct_option' => 'B',
                'sort_order' => 3,
            ],
            [
                'question_text' => 'Which Surah does not begin with Bismillah?',
                'option_a' => 'Surah At-Tawbah',
                'option_b' => 'Surah Al-Anfal',
                'option_c' => 'Surah Yunus',
                'option_d' => 'Surah Al-Kahf',
                'correct_option' => 'A',
                'sort_order' => 4,
            ],
            [
                'question_text' => 'Which Surah is known as the "Heart of the Quran"?',
                'option_a' => 'Surah Ar-Rahman',
                'option_b' => 'Surah Al-Mulk',
                'option_c' => 'Surah Ya-Sin',
                'option_d' => 'Surah Al-Waqi\'ah',
                'correct_option' => 'C',
                'sort_order' => 5,
            ],
            [
                'question_text' => 'How many Juz (parts) are in the Holy Quran?',
                'option_a' => '25',
                'option_b' => '30',
                'option_c' => '35',
                'option_d' => '40',
                'correct_option' => 'B',
                'sort_order' => 6,
            ],
        ];

        foreach ($questionsData as $qData) {
            Question::updateOrCreate(
                [
                    'quiz_week_id' => $week1->id,
                    'sort_order' => $qData['sort_order'],
                ],
                $qData
            );
        }

        // 4. Create sample participants for rich leaderboard
        $participants = [
            ['name' => 'Fathima Beevi', 'whatsapp_number' => '9000000001', 'locality' => 'Palayam', 'territory' => 'Kozhikode', 'age' => 22, 'score' => 30],
            ['name' => 'Rayan Abdullah', 'whatsapp_number' => '9000000002', 'locality' => 'Kaloor', 'territory' => 'Kochi', 'age' => 24, 'score' => 30],
            ['name' => 'Amina Zahra', 'whatsapp_number' => '9000000003', 'locality' => 'Round North', 'territory' => 'Thrissur', 'age' => 21, 'score' => 30],
            ['name' => 'Mohammed Zayan', 'whatsapp_number' => '9000000004', 'locality' => 'Manjeri', 'territory' => 'Malappuram', 'age' => 26, 'score' => 20],
            ['name' => 'Aisha Maryam', 'whatsapp_number' => '9000000005', 'locality' => 'Thalassery', 'territory' => 'Kannur', 'age' => 19, 'score' => 20],
            ['name' => 'Bilal Ahmed', 'whatsapp_number' => '9000000006', 'locality' => 'Chinnakada', 'territory' => 'Kollam', 'age' => 25, 'score' => 20],
            ['name' => 'Maryam Haneef', 'whatsapp_number' => '9000000007', 'locality' => 'Fort', 'territory' => 'Palakkad', 'age' => 23, 'score' => 10],
            ['name' => 'Faisal Rahman', 'whatsapp_number' => '9000000008', 'locality' => 'Feroke', 'territory' => 'Kozhikode', 'age' => 27, 'score' => 10],
            ['name' => 'Sana Basheer', 'whatsapp_number' => '9000000009', 'locality' => 'Aluva', 'territory' => 'Kochi', 'age' => 20, 'score' => 10],
            ['name' => 'Umar Farooq', 'whatsapp_number' => '9000000010', 'locality' => 'Guruvayur', 'territory' => 'Thrissur', 'age' => 28, 'score' => 10],
            // Demo test account
            ['name' => 'Saifudheen Test', 'whatsapp_number' => '9000000024', 'locality' => 'Kuttichira', 'territory' => 'Kozhikode', 'age' => 25, 'score' => null],
        ];

        foreach ($participants as $p) {
            $user = User::updateOrCreate(
                ['whatsapp_number' => $p['whatsapp_number']],
                [
                    'name' => $p['name'],
                    'locality' => $p['locality'],
                    'territory' => $p['territory'],
                    'age' => $p['age'],
                    'whatsapp_verified' => true,
                    'status' => 'active',
                ]
            );

            // If score is specified, generate an attempt and submit it
            if ($p['score'] !== null) {
                $existingAttempt = QuizAttempt::where('user_id', $user->id)
                    ->where('quiz_week_id', $week1->id)
                    ->first();

                if (!$existingAttempt) {
                    $attempt = $service->startAttempt($user, $week1);

                    $answers = [];
                    $assigned = $attempt->questions;
                    $targetScore = $p['score'];
                    $correctNeeded = (int) ($targetScore / 10);

                    $correctCount = 0;
                    foreach ($assigned as $q) {
                        if ($correctCount < $correctNeeded) {
                            $answers[$q->id] = $q->correct_option;
                            $correctCount++;
                        } else {
                            $answers[$q->id] = ($q->correct_option === 'A') ? 'B' : 'A';
                        }
                    }

                    $service->submitAttempt($attempt, $answers);
                }
            }
        }

        // 5. Conduct sample lucky draw winner for Week 1 so Winner card displays prominently
        $firstWinner = User::where('name', 'Fathima Beevi')->first();
        if ($firstWinner) {
            LuckyDraw::updateOrCreate(
                ['quiz_week_id' => $week1->id],
                [
                    'winner_user_id' => $firstWinner->id,
                    'status' => LuckyDraw::STATUS_PUBLISHED,
                    'drawn_at' => $now,
                    'confirmed_at' => $now,
                    'published_at' => $now,
                    'notes' => 'Weekly lucky draw selected from 30/30 participants.',
                ]
            );
        }

        \Illuminate\Support\Facades\Cache::flush();
    }
}
