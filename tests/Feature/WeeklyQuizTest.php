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
    public function test_can_create_weekly_quiz_and_requires_valid_questions_to_publish(): void
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

        // Add 8 questions
        for ($i = 1; $i <= 8; $i++) {
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

    public function test_participant_gets_five_random_questions_and_fixed_attempt(): void
    {
        $weekNum = (QuizWeek::max('week_number') ?? 0) + 11;
        $quizWeek = QuizWeek::create([
            'week_number' => $weekNum,
            'quiz_date' => Carbon::today(),
            'start_at' => Carbon::now()->subMinutes(10),
            'end_at' => Carbon::now()->addMinutes(50),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        for ($i = 1; $i <= 8; $i++) {
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

        $this->assertEquals(5, $attempt->questions()->count());

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

        for ($i = 1; $i <= 8; $i++) {
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

        // Submit all 5 correct answers
        $answers1 = [];
        foreach ($attempt1->questions as $q) {
            $answers1[$q->id] = 'B';
        }

        $completedAttempt1 = $service->submitAttempt($attempt1, $answers1);
        $this->assertEquals(50, $completedAttempt1->weekly_score);
        $this->assertEquals(50, $user1->fresh()->total_points);

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
        $quizWeek = QuizWeek::create([
            'week_number' => (QuizWeek::max('week_number') ?? 0) + 1,
            'quiz_date' => Carbon::today(),
            'start_at' => Carbon::now()->subMinutes(10),
            'end_at' => Carbon::now()->addMinutes(50),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        for ($i = 1; $i <= 8; $i++) {
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
        $this->assertCount(5, $questions);

        // Verify correct_option is NOT exposed
        foreach ($questions as $q) {
            $this->assertArrayNotHasKey('correct_option', $q);
            $this->assertCount(4, $q['options']);
        }

        // 2. Submit answers
        $submitResp = $this->postJson('/quiz/api/submit', [
            'answers' => [0, 1, 2, 0, 1],
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

        // 1. International Registration with Password & Email (UAE without leading zero)
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

        // 2. Login with Password & Full Phone (+971...)
        $loginResp = $this->postJson('/quiz/api/auth/login-password', [
            'identifier' => '+971' . $uaePhone,
            'password' => 'secret123',
        ]);
        $loginResp->assertStatus(200);
        $loginResp->assertJsonPath('name', 'Ahmed UAE');
        $this->assertAuthenticated('web');

        // Logout
        $this->postJson('/quiz/api/auth/logout');

        // 3. Login with Local Phone + country_code parameter
        $loginLocalResp = $this->postJson('/quiz/api/auth/login-password', [
            'identifier' => $uaePhone,
            'country_code' => '+971',
            'password' => 'secret123',
        ]);
        $loginLocalResp->assertStatus(200);
        $loginLocalResp->assertJsonPath('name', 'Ahmed UAE');

        // 4. Test wrong password
        $this->postJson('/quiz/api/auth/logout');
        $failResp = $this->postJson('/quiz/api/auth/login-password', [
            'identifier' => $email,
            'password' => 'wrongpass',
        ]);
        $failResp->assertStatus(422);
        $failResp->assertJsonPath('code', 'INVALID_PASSWORD');
    }

    public function test_multi_country_support_saudi_qatar_and_leading_zeros(): void
    {
        // 1. Saudi Arabia with leading zero (e.g. 055xxxxxxx)
        $saudiLocal = rand(1000000, 9999999);
        $saudiPhoneInput = '055' . $saudiLocal;
        $saudiEmail = "saudi{$saudiLocal}@test.com";

        $saudiReg = $this->postJson('/quiz/api/auth/register-password', [
            'name' => 'Faisal Riyadh',
            'place' => 'Riyadh',
            'country_code' => '+966',
            'phone' => $saudiPhoneInput,
            'email' => $saudiEmail,
            'password' => 'password123',
        ]);
        $saudiReg->assertStatus(200);
        $saudiReg->assertJsonPath('phone', '+96655' . $saudiLocal);

        $this->postJson('/quiz/api/auth/logout');

        // Login with Saudi phone without leading 0 and with country code
        $saudiLogin = $this->postJson('/quiz/api/auth/login-password', [
            'identifier' => '55' . $saudiLocal,
            'country_code' => '+966',
            'password' => 'password123',
        ]);
        $saudiLogin->assertStatus(200);
        $saudiLogin->assertJsonPath('name', 'Faisal Riyadh');

        $this->postJson('/quiz/api/auth/logout');

        // 2. Qatar registration (8 digits)
        $qatarNum = rand(100000, 999999);
        $qatarPhone = '55' . $qatarNum;
        $qatarEmail = "qatar{$qatarNum}@test.com";

        $qatarReg = $this->postJson('/quiz/api/auth/register-password', [
            'name' => 'Rashid Doha',
            'place' => 'Doha',
            'country_code' => '+974',
            'phone' => $qatarPhone,
            'email' => $qatarEmail,
            'password' => 'qatar123',
        ]);
        $qatarReg->assertStatus(200);
        $qatarReg->assertJsonPath('phone', '+974' . $qatarPhone);

        $this->postJson('/quiz/api/auth/logout');

        // Login using email
        $emailLogin = $this->postJson('/quiz/api/auth/login-password', [
            'identifier' => $qatarEmail,
            'password' => 'qatar123',
        ]);
        $emailLogin->assertStatus(200);
        $emailLogin->assertJsonPath('name', 'Rashid Doha');

        // 3. Duplicate phone registration check
        $dupReg = $this->postJson('/quiz/api/auth/register-password', [
            'name' => 'Duplicate Attempt',
            'country_code' => '+974',
            'phone' => $qatarPhone,
            'email' => 'other_unique@test.com',
            'password' => 'password123',
        ]);
        $dupReg->assertStatus(409);
        $dupReg->assertJsonPath('code', 'EXISTS');

        // 4. Invalid phone format check (e.g. 4 digits for Qatar)
        $invalidReg = $this->postJson('/quiz/api/auth/register-password', [
            'name' => 'Invalid Phone',
            'country_code' => '+974',
            'phone' => '1234',
            'email' => 'invalid_phone@test.com',
            'password' => 'password123',
        ]);
        $invalidReg->assertStatus(422);
        $invalidReg->assertJsonPath('code', 'BADPHONE');
    }

    public function test_auto_detect_dial_code_from_pasted_phone_and_indian_defaults(): void
    {
        // 1. User pastes full phone with +971 into phone input (e.g. +971 50 765 4321)
        $num = rand(1000000, 9999999);
        $pastedPhone = "+971 50 {$num}";
        $email = "pasted{$num}@test.com";

        $reg = $this->postJson('/quiz/api/auth/register-password', [
            'name' => 'Pasted UAE',
            'place' => 'Dubai',
            'phone' => $pastedPhone,
            'email' => $email,
            'password' => 'password123',
        ]);
        $reg->assertStatus(200);
        $reg->assertJsonPath('phone', "+97150{$num}");

        $this->postJson('/quiz/api/auth/logout');

        // 2. User logs in by typing full number with international prefix 00971...
        $login = $this->postJson('/quiz/api/auth/login-password', [
            'identifier' => "0097150{$num}",
            'password' => 'password123',
        ]);
        $login->assertStatus(200);
        $login->assertJsonPath('name', 'Pasted UAE');

        $this->postJson('/quiz/api/auth/logout');

        // 3. Indian default registration (10 digits starting with 9)
        $inNum = '98' . rand(10000000, 99999999);
        $inEmail = "india{$inNum}@test.com";

        $inReg = $this->postJson('/quiz/api/auth/register-password', [
            'name' => 'Kerala Participant',
            'place' => 'Kozhikode',
            'phone' => $inNum,
            'email' => $inEmail,
            'password' => 'kerala123',
        ]);
        $inReg->assertStatus(200);
        $inReg->assertJsonPath('phone', "+91{$inNum}");

        $this->postJson('/quiz/api/auth/logout');

        // Login with 10 digits without +91
        $inLogin = $this->postJson('/quiz/api/auth/login-password', [
            'identifier' => $inNum,
            'password' => 'kerala123',
        ]);
        $inLogin->assertStatus(200);
        $inLogin->assertJsonPath('name', 'Kerala Participant');

        // Invalid Indian mobile number (e.g. 9 digits)
        $invalidIn = $this->postJson('/quiz/api/auth/register-password', [
            'name' => 'Bad Indian Phone',
            'country_code' => '+91',
            'phone' => '987654321', // 9 digits
            'email' => 'bad_in@test.com',
            'password' => 'kerala123',
        ]);
        $invalidIn->assertStatus(422);
        $invalidIn->assertJsonPath('code', 'BADPHONE');
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

    public function test_participant_session_persists_with_remember_cookie_and_api_me(): void
    {
        $user = User::factory()->create([
            'name' => 'Session Participant',
            'whatsapp_number' => '+919988112233',
            'locality' => 'Kozhikode',
            'territory' => 'Malappuram',
            'whatsapp_verified' => true,
        ]);

        // Login via OTP
        $this->postJson('/quiz/api/auth/send-otp', [
            'phone' => '9988112233',
            'register' => 0,
        ]);

        $verifyResp = $this->postJson('/quiz/api/auth/verify-otp', [
            'phone' => '9988112233',
            'code' => '123456',
            'register' => 0,
        ]);

        $verifyResp->assertStatus(200);
        $this->assertAuthenticated('web');

        // Check remember_token was generated and saved
        $user->refresh();
        $this->assertNotEmpty($user->remember_token);

        // Check /quiz/api/auth/me returns current user
        $meResp = $this->getJson('/quiz/api/auth/me');
        $meResp->assertStatus(200);
        $meResp->assertJsonPath('user.id', $user->id);
        $meResp->assertJsonPath('user.name', 'Session Participant');

        // Check manual logout clears session
        $logoutResp = $this->postJson('/quiz/api/auth/logout');
        $logoutResp->assertStatus(200);
        $this->assertGuest('web');

        // Check /quiz/api/auth/me returns null after logout
        $meAfterLogout = $this->getJson('/quiz/api/auth/me');
        $meAfterLogout->assertStatus(200);
        $meAfterLogout->assertJsonPath('user', null);
    }
}


