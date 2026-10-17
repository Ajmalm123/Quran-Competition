<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\LuckyDraw;
use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LuckyDrawStageTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        QuizWeek::where('week_number', 1)->delete();
    }

    public function test_lucky_draw_page_renders_successfully(): void
    {
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );

        $week = QuizWeek::create([
            'week_number' => 1,
            'quiz_date' => now()->toDateString(),
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        $response = $this->actingAs($admin, 'admin')->get('/admin/lucky-draw');
        $response->assertStatus(200);
        $response->assertSee('window.__INITIAL_DATA__', false);
        $response->assertSee('window.__CSRF_TOKEN__', false);
        $response->assertSee('lucky-draw.js');
        $response->assertSee('lucky-draw.css');
    }

    public function test_unauthenticated_guest_cannot_access_lucky_draw_stage(): void
    {
        // Unauthenticated guest must be redirected to admin login
        $guestResponse = $this->get('/admin/lucky-draw');
        $guestResponse->assertRedirect('/admin/login');

        // Public route /lucky-draw must not exist
        $publicResponse = $this->get('/lucky-draw');
        $publicResponse->assertStatus(404);
    }

    public function test_stage_data_returns_only_perfect_score_50_completed_attempts(): void
    {
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );

        $week = QuizWeek::create([
            'week_number' => 1,
            'quiz_date' => now()->toDateString(),
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        $perfectUser = User::factory()->create(['name' => 'Perfect Scorer', 'place' => 'Malappuram']);
        QuizAttempt::create([
            'user_id' => $perfectUser->id,
            'quiz_week_id' => $week->id,
            'weekly_score' => 50,
            'status' => QuizAttempt::STATUS_COMPLETED,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $imperfectUser = User::factory()->create(['name' => 'Low Scorer']);
        QuizAttempt::create([
            'user_id' => $imperfectUser->id,
            'quiz_week_id' => $week->id,
            'weekly_score' => 40,
            'status' => QuizAttempt::STATUS_COMPLETED,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $inProgressUser = User::factory()->create(['name' => 'In Progress']);
        QuizAttempt::create([
            'user_id' => $inProgressUser->id,
            'quiz_week_id' => $week->id,
            'weekly_score' => 50,
            'status' => QuizAttempt::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')->getJson("/admin/lucky-draw/api/data?week_id={$week->id}");
        $response->assertStatus(200);

        $participants = $response->json('eligibleParticipants');
        $this->assertCount(1, $participants);
        $this->assertEquals($perfectUser->id, $participants[0]['id']);
        $this->assertEquals('Perfect Scorer', $participants[0]['name']);
        $this->assertEquals('Malappuram', $participants[0]['place']);
    }

    public function test_stage_handles_zero_eligible_participants_gracefully(): void
    {
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );

        $week = QuizWeek::create([
            'week_number' => 1,
            'quiz_date' => now()->toDateString(),
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        $response = $this->actingAs($admin, 'admin')->getJson("/admin/lucky-draw/api/data?week_id={$week->id}");
        $response->assertStatus(200);

        $this->assertIsArray($response->json('eligibleParticipants'));
        $this->assertCount(0, $response->json('eligibleParticipants'));
    }

    public function test_publish_winner_rejects_participant_without_50_out_of_50(): void
    {
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );

        $week = QuizWeek::create([
            'week_number' => 1,
            'quiz_date' => now()->toDateString(),
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        $nonScorer = User::factory()->create();

        $response = $this->actingAs($admin, 'admin')->postJson('/admin/lucky-draw/api/publish', [
            'week_id' => $week->id,
            'winner_user_id' => $nonScorer->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertDatabaseMissing('lucky_draws', [
            'quiz_week_id' => $week->id,
            'winner_user_id' => $nonScorer->id,
        ]);
    }

    public function test_publish_winner_succeeds_and_publishes_to_public_schedule(): void
    {
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );

        $week = QuizWeek::create([
            'week_number' => 1,
            'quiz_date' => now()->toDateString(),
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        $winner = User::factory()->create(['name' => 'Grand Winner']);
        QuizAttempt::create([
            'user_id' => $winner->id,
            'quiz_week_id' => $week->id,
            'weekly_score' => 50,
            'status' => QuizAttempt::STATUS_COMPLETED,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')->postJson('/admin/lucky-draw/api/publish', [
            'week_id' => $week->id,
            'winner_user_id' => $winner->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('lucky_draws', [
            'quiz_week_id' => $week->id,
            'winner_user_id' => $winner->id,
            'status' => LuckyDraw::STATUS_PUBLISHED,
        ]);

        // Check redraw flow
        $redrawResponse = $this->actingAs($admin, 'admin')->postJson('/admin/lucky-draw/api/redraw', [
            'week_id' => $week->id,
        ]);

        $redrawResponse->assertStatus(200);
        $redrawResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('lucky_draws', [
            'quiz_week_id' => $week->id,
            'winner_user_id' => null,
            'status' => LuckyDraw::STATUS_PENDING,
        ]);
    }

    public function test_unauthorized_guests_cannot_publish_or_redraw(): void
    {
        $week = QuizWeek::create([
            'week_number' => 1,
            'quiz_date' => now()->toDateString(),
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'status' => QuizWeek::STATUS_LIVE,
        ]);

        $response = $this->postJson('/admin/lucky-draw/api/publish', [
            'week_id' => $week->id,
            'winner_user_id' => 1,
        ]);

        $response->assertStatus(401);

        $redrawResponse = $this->postJson('/admin/lucky-draw/api/redraw', [
            'week_id' => $week->id,
        ]);

        $redrawResponse->assertStatus(401);
    }
}
