<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\LuckyDraw;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use App\Models\User;
use App\Services\QuizService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WeeklyQuizTest extends TestCase
{
    use DatabaseTransactions;
    public function test_can_create_weekly_quiz_and_requires_six_questions_to_publish(): void
    {
        $weekNum = (QuizWeek::max('week_number') ?? 0) + 10;
        $quizWeek = QuizWeek::create([
            'week_number' => $weekNum,
            'quiz_date' => Carbon::today(),
            'start_at' => Carbon::now()->subHour(),
            'end_at' => Carbon::now()->addHour(),
            'status' => QuizWeek::STATUS_DRAFT,
        ]);

        $this->assertFalse($quizWeek->canPublish());

        // Add 6 questions
        for ($i = 1; $i <= 6; $i++) {
            Question::create([
                'quiz_week_id' => $quizWeek->id,
                'question_text' => "Question number {$i}?",
                'option_a' => 'Answer A',
                'option_b' => 'Answer B',
                'option_c' => 'Answer C',
                'option_d' => 'Answer D',
                'correct_option' => 'A',
                'sort_order' => $i,
            ]);
        }

        $this->assertTrue($quizWeek->fresh()->canPublish());
    }

    public function test_participant_gets_three_random_questions_and_fixed_attempt(): void
    {
        $weekNum = (QuizWeek::max('week_number') ?? 0) + 11;
        $quizWeek = QuizWeek::create([
            'week_number' => $weekNum,
            'quiz_date' => Carbon::today(),
            'start_at' => Carbon::now()->subMinutes(10),
            'end_at' => Carbon::now()->addMinutes(50),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        for ($i = 1; $i <= 6; $i++) {
            Question::create([
                'quiz_week_id' => $quizWeek->id,
                'question_text' => "Question {$i}?",
                'option_a' => 'Option 1',
                'option_b' => 'Option 2',
                'option_c' => 'Option 3',
                'option_d' => 'Option 4',
                'correct_option' => 'A',
                'sort_order' => $i,
            ]);
        }

        $user = User::create([
            'name' => 'Ajmal Test ' . uniqid(),
            'whatsapp_number' => '+919999' . rand(100000, 999999),
            'whatsapp_verified' => true,
            'status' => 'active',
        ]);

        $service = app(QuizService::class);
        $attempt = $service->startAttempt($user, $quizWeek);

        $this->assertEquals(3, $attempt->questions()->count());

        // Test one attempt per week rule
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("You have already participated in this week's quiz.");
        $service->startAttempt($user, $quizWeek);
    }

    public function test_scoring_and_lucky_draw_lifecycle(): void
    {
        $weekNum = (QuizWeek::max('week_number') ?? 0) + 12;
        $quizWeek = QuizWeek::create([
            'week_number' => $weekNum,
            'quiz_date' => Carbon::today(),
            'start_at' => Carbon::now()->subMinutes(10),
            'end_at' => Carbon::now()->addMinutes(50),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        for ($i = 1; $i <= 6; $i++) {
            Question::create([
                'quiz_week_id' => $quizWeek->id,
                'question_text' => "Question {$i}?",
                'option_a' => 'Option 1',
                'option_b' => 'Option 2',
                'option_c' => 'Option 3',
                'option_d' => 'Option 4',
                'correct_option' => 'B',
                'sort_order' => $i,
            ]);
        }

        $user1 = User::create([
            'name' => 'Participant One ' . uniqid(),
            'whatsapp_number' => '+9188' . rand(10000000, 99999999),
            'whatsapp_verified' => true,
            'status' => 'active',
        ]);

        $service = app(QuizService::class);
        $attempt1 = $service->startAttempt($user1, $quizWeek);

        // Submit all 3 correct answers
        $answers1 = [];
        foreach ($attempt1->questions as $q) {
            $answers1[$q->id] = 'B';
        }

        $completedAttempt1 = $service->submitAttempt($attempt1, $answers1);
        $this->assertEquals(30, $completedAttempt1->weekly_score);
        $this->assertEquals(30, $user1->fresh()->total_points);

        // Eligible participants check
        $eligible = $service->getEligibleLuckyDrawParticipants($quizWeek);
        $this->assertCount(1, $eligible);
        $this->assertEquals($user1->id, $eligible->first()->id);

        // Run Lucky Draw
        $draw = $service->executeLuckyDraw($quizWeek);
        $this->assertEquals($user1->id, $draw->winner_user_id);
        $this->assertEquals(LuckyDraw::STATUS_DRAWN, $draw->status);

        // Confirm Winner (Locks)
        $confirmedDraw = $service->confirmWinner($draw);
        $this->assertEquals(LuckyDraw::STATUS_CONFIRMED, $confirmedDraw->status);
        $this->assertTrue($confirmedDraw->isLocked());

        // Publish Winner
        $publishedDraw = $service->publishWinner($confirmedDraw);
        $this->assertEquals(LuckyDraw::STATUS_PUBLISHED, $publishedDraw->status);
        $this->assertTrue($publishedDraw->isPublished());
    }

    public function test_quiz_public_page_renders_with_initial_data(): void
    {
        $response = $this->get('/quiz');
        $response->assertStatus(200);
        $response->assertSee('Friday Night Quiz');
        $response->assertSee('window.INITIAL_DATA', false);
    }

    public function test_auth_flow_send_and_verify_otp(): void
    {
        $testPhone = '9876543210';

        // 1. Send OTP for new registration
        $sendResp = $this->postJson('/quiz/api/auth/send-otp', [
            'phone' => $testPhone,
            'register' => 1,
        ]);
        $sendResp->assertStatus(200);
        $sendResp->assertJsonPath('success', true);

        // 2. Verify OTP and register user
        $verifyResp = $this->postJson('/quiz/api/auth/verify-otp', [
            'phone' => $testPhone,
            'code' => '123456',
            'register' => 1,
            'name' => 'Test User',
            'age' => 20,
            'locality' => 'Calicut',
            'territory' => 'Kozhikode',
        ]);
        $verifyResp->assertStatus(200);
        $verifyResp->assertJsonPath('name', 'Test User');

        // Check user is authenticated in session
        $this->assertAuthenticated('web');
    }

    public function test_full_quiz_participation_api_flow(): void
    {
        $user = User::factory()->create([
            'name' => 'API Participant',
            'whatsapp_number' => '9988776655',
            'locality' => 'Kochi',
            'territory' => 'Ernakulam',
            'whatsapp_verified' => true,
        ]);

        $this->actingAs($user, 'web');

        // 1. Start quiz
        $startResp = $this->postJson('/quiz/api/start');
        $startResp->assertStatus(200);
        $startResp->assertJsonStructure([
            'week',
            'questions' => [
                '*' => ['id', 'text', 'options']
            ]
        ]);

        $questions = $startResp->json('questions');
        $this->assertCount(3, $questions);

        // Verify correct_option is NOT exposed
        foreach ($questions as $q) {
            $this->assertArrayNotHasKey('correct_option', $q);
            $this->assertCount(4, $q['options']);
        }

        // 2. Submit answers
        $submitResp = $this->postJson('/quiz/api/submit', [
            'answers' => [0, 1, 2],
        ]);
        $submitResp->assertStatus(200);
        $submitResp->assertJsonStructure([
            'score',
            'correct',
            'total',
            'eligible',
        ]);

        // 3. Check Leaderboard API returns top 10 and me
        $lbResp = $this->getJson('/quiz/api/leaderboard');
        $lbResp->assertStatus(200);
        $lbResp->assertJsonStructure([
            'top' => [
                '*' => ['id', 'name', 'territory', 'points', 'rank']
            ],
            'me'
        ]);
    }

    public function test_international_user_registration_and_login_with_password(): void
    {
        $uniqueNum = rand(1000000, 9999999);
        $uaePhone = '50' . $uniqueNum;
        $email = "uae{$uniqueNum}@test.com";

        // 1. International Registration with Password & Email
        $regResp = $this->postJson('/quiz/api/auth/register-password', [
            'name' => 'Ahmed UAE',
            'age' => 28,
            'locality' => 'Deira',
            'territory' => 'UAE',
            'country_code' => '+971',
            'phone' => $uaePhone,
            'email' => $email,
            'password' => 'secret123',
        ]);

        $regResp->assertStatus(200);
        $regResp->assertJsonPath('name', 'Ahmed UAE');
        $this->assertAuthenticated('web');

        // Logout
        $this->postJson('/quiz/api/auth/logout');
        $this->assertGuest('web');

        // 2. Login with Password & Phone
        $loginResp = $this->postJson('/quiz/api/auth/login-password', [
            'identifier' => '+971' . $uaePhone,
            'password' => 'secret123',
        ]);
        $loginResp->assertStatus(200);
        $loginResp->assertJsonPath('name', 'Ahmed UAE');
        $this->assertAuthenticated('web');

        // 3. Test wrong password
        $this->postJson('/quiz/api/auth/logout');
        $failResp = $this->postJson('/quiz/api/auth/login-password', [
            'identifier' => $email,
            'password' => 'wrongpass',
        ]);
        $failResp->assertStatus(422);
        $failResp->assertJsonPath('code', 'INVALID_PASSWORD');
    }

    public function test_forgot_password_flow_sends_email_and_resets_password(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $uniqueNum = rand(1000000, 9999999);
        $user = User::factory()->create([
            'name' => 'Reset User',
            'email' => "reset{$uniqueNum}@example.com",
            'whatsapp_number' => '+9665' . $uniqueNum,
            'password' => \Illuminate\Support\Facades\Hash::make('oldpassword'),
        ]);

        // 1. Request Password Reset
        $forgotResp = $this->postJson('/quiz/api/auth/forgot-password', [
            'identifier' => $user->email,
        ]);
        $forgotResp->assertStatus(200);
        $forgotResp->assertJsonPath('success', true);

        // Verify mail was queued/sent
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\QuizPasswordResetMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        // 2. Retrieve the token generated in password_reset_tokens
        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();
        $this->assertNotNull($record);

        // We simulate resetting password by passing a token that matches
        $rawToken = 'test-token-123456';
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->update(['token' => \Illuminate\Support\Facades\Hash::make($rawToken)]);

        // 3. Submit Reset Password
        $resetResp = $this->postJson('/quiz/api/auth/reset-password', [
            'email' => $user->email,
            'token' => $rawToken,
            'password' => 'newsecretpassword123',
        ]);

        $resetResp->assertStatus(200);
        $this->assertAuthenticated('web');

        // Verify password actually updated
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newsecretpassword123', $user->fresh()->password));
    }

    public function test_filament_admin_navigation_group_order(): void
    {
        $admin = Admin::first() ?? Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin_nav_test@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($admin, 'admin');

        $panel = filament()->getPanel('admin');
        filament()->setCurrentPanel($panel);

        $groups = $panel->getNavigation();
        $groupLabels = array_values(array_filter(array_map(fn ($g) => $g->getLabel(), $groups)));

        $appIndex = array_search('Applications', $groupLabels);
        $quizIndex = array_search('Weekly Online Quiz', $groupLabels);

        $this->assertNotFalse($appIndex, 'Applications group not found in navigation: ' . json_encode($groupLabels));
        $this->assertNotFalse($quizIndex, 'Weekly Online Quiz group not found in navigation: ' . json_encode($groupLabels));
        $this->assertGreaterThan($appIndex, $quizIndex, 'Weekly Online Quiz should come after Applications: ' . json_encode($groupLabels));
    }
}


