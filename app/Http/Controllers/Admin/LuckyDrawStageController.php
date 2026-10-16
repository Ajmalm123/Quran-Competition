<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LuckyDraw;
use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use App\Models\User;
use App\Services\QuizService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class LuckyDrawStageController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {
    }

    /**
     * Display the full-screen dynamic Lucky Draw Stage.
     */
    public function index(Request $request): View
    {
        $selectedWeekId = $request->query('week_id');
        $initialData = $this->getStageData($selectedWeekId);

        return view('admin.lucky-draw', [
            'initialData' => $initialData,
            'csrfToken' => csrf_token(),
        ]);
    }

    /**
     * API endpoint to get data for a specific quiz week.
     */
    public function getData(Request $request): JsonResponse
    {
        $selectedWeekId = $request->query('week_id');
        $data = $this->getStageData($selectedWeekId);

        return response()->json($data);
    }

    /**
     * API endpoint to publish the selected winner to the database and live public website.
     */
    public function publishWinner(Request $request): JsonResponse
    {
        $request->validate([
            'week_id' => 'required|integer|exists:quiz_weeks,id',
            'winner_user_id' => 'required|integer|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $quizWeek = QuizWeek::findOrFail($request->input('week_id'));
        $winner = User::findOrFail($request->input('winner_user_id'));

        // Verify that the winner actually scored 50/50 in this week
        $hasPerfectScore = QuizAttempt::where('quiz_week_id', $quizWeek->id)
            ->where('user_id', $winner->id)
            ->where('status', QuizAttempt::STATUS_COMPLETED)
            ->where('weekly_score', 50)
            ->exists();

        if (!$hasPerfectScore) {
            return response()->json([
                'success' => false,
                'message' => "Participant {$winner->name} does not have a 50/50 score for Week {$quizWeek->week_number}.",
            ], 422);
        }

        $now = Carbon::now();
        $adminUser = Auth::guard('admin')->user();
        $adminName = $adminUser ? $adminUser->name : 'Admin';

        $luckyDraw = LuckyDraw::updateOrCreate(
            ['quiz_week_id' => $quizWeek->id],
            [
                'winner_user_id' => $winner->id,
                'status' => LuckyDraw::STATUS_PUBLISHED,
                'drawn_at' => $now,
                'confirmed_at' => $now,
                'published_at' => $now,
                'notes' => $request->input('notes') ?? "Winner drawn and published via Live Lucky Draw Ceremony by {$adminName}.",
            ]
        );

        Cache::forget('quiz_public_schedule');

        return response()->json([
            'success' => true,
            'message' => "Winner {$winner->name} published successfully! It is now live on the public website.",
            'lucky_draw' => [
                'id' => $luckyDraw->id,
                'status' => $luckyDraw->status,
                'winner_user_id' => $luckyDraw->winner_user_id,
                'winner' => [
                    'id' => $winner->id,
                    'name' => $winner->name,
                    'place' => !empty($winner->place) ? $winner->place : (!empty($winner->locality) ? $winner->locality : (!empty($winner->territory) ? $winner->territory : '')),
                    'whatsapp_number' => $winner->whatsapp_number,
                    'locality' => $winner->locality,
                    'territory' => $winner->territory,
                ],
                'drawn_at' => $luckyDraw->drawn_at?->toISOString(),
                'published_at' => $luckyDraw->published_at?->toISOString(),
            ],
        ]);
    }

    /**
     * API endpoint to re-draw / reset the lucky draw for a week.
     */
    public function redraw(Request $request): JsonResponse
    {
        $request->validate([
            'week_id' => 'required|integer|exists:quiz_weeks,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $quizWeek = QuizWeek::findOrFail($request->input('week_id'));
        $adminUser = Auth::guard('admin')->user();
        $adminName = $adminUser ? $adminUser->name : 'Admin';

        $luckyDraw = LuckyDraw::where('quiz_week_id', $quizWeek->id)->first();
        if ($luckyDraw) {
            $luckyDraw->update([
                'winner_user_id' => null,
                'status' => LuckyDraw::STATUS_PENDING,
                'drawn_at' => null,
                'confirmed_at' => null,
                'published_at' => null,
                'notes' => 'Reset for re-draw: ' . ($request->input('reason') ?? "Triggered from Live Lucky Draw Ceremony by {$adminName}."),
            ]);
        }

        Cache::forget('quiz_public_schedule');

        return response()->json([
            'success' => true,
            'message' => "Lucky draw for Week {$quizWeek->week_number} has been reset. You can now choose a new winner.",
        ]);
    }

    /**
     * Build the structured stage data for the frontend.
     */
    protected function getStageData($selectedWeekId = null): array
    {
        $allWeeks = QuizWeek::with(['luckyDraw.winner'])
            ->orderBy('week_number', 'asc')
            ->get();

        // Determine current week
        $currentWeek = null;
        if ($selectedWeekId) {
            $currentWeek = $allWeeks->firstWhere('id', (int) $selectedWeekId)
                ?? $allWeeks->firstWhere('week_number', (int) $selectedWeekId);
        }

        if (!$currentWeek) {
            // Priority:
            // 1. First week that has pending/drawn lucky draw
            // 2. Week that is LIVE or COMPLETED
            // 3. First week
            $currentWeek = $allWeeks->first(function (QuizWeek $w) {
                return $w->luckyDraw && in_array($w->luckyDraw->status, [LuckyDraw::STATUS_PENDING, LuckyDraw::STATUS_DRAWN]);
            }) ?? $allWeeks->first(function (QuizWeek $w) {
                return in_array($w->status, [QuizWeek::STATUS_LIVE, QuizWeek::STATUS_COMPLETED]);
            }) ?? $allWeeks->first();
        }

        // Summary of all weeks for the week-picker dropdown
        $weeksSummary = $allWeeks->map(function (QuizWeek $w) {
            $eligibleCount = QuizAttempt::where('quiz_week_id', $w->id)
                ->where('status', QuizAttempt::STATUS_COMPLETED)
                ->where('weekly_score', 50)
                ->count();

            $winner = null;
            if ($w->luckyDraw && $w->luckyDraw->winner) {
                $winnerUser = $w->luckyDraw->winner;
                $winner = [
                    'id' => $winnerUser->id,
                    'name' => $winnerUser->name,
                    'place' => !empty($winnerUser->place) ? $winnerUser->place : (!empty($winnerUser->locality) ? $winnerUser->locality : (!empty($winnerUser->territory) ? $winnerUser->territory : '')),
                    'locality' => $winnerUser->locality,
                    'territory' => $winnerUser->territory,
                ];
            }

            return [
                'id' => (int) $w->id,
                'week_number' => (int) $w->week_number,
                'quiz_date' => $w->quiz_date ? Carbon::parse($w->quiz_date)->format('d M Y') : '—',
                'status' => $w->status,
                'lucky_draw_status' => $w->luckyDraw?->status ?? LuckyDraw::STATUS_PENDING,
                'eligible_count' => $eligibleCount,
                'winner' => $winner,
            ];
        })->values()->toArray();

        // Get 50/50 eligible participants for the current week
        $eligibleParticipants = [];
        $luckyDrawData = null;

        if ($currentWeek) {
            $eligibleUsers = User::whereHas('quizAttempts', function ($q) use ($currentWeek) {
                $q->where('quiz_week_id', $currentWeek->id)
                    ->where('status', QuizAttempt::STATUS_COMPLETED)
                    ->where('weekly_score', 50);
            })->orderBy('name', 'asc')->get();

            $eligibleParticipants = $eligibleUsers->map(function (User $u) {
                return [
                    'id' => (int) $u->id,
                    'name' => $u->name,
                    'place' => !empty($u->place) ? $u->place : (!empty($u->locality) ? $u->locality : (!empty($u->territory) ? $u->territory : '')),
                    'whatsapp_number' => $u->whatsapp_number,
                    'locality' => $u->locality ?? '',
                    'territory' => $u->territory ?? '',
                ];
            })->values()->toArray();

            $draw = $currentWeek->luckyDraw;
            if ($draw) {
                $winnerInfo = null;
                if ($draw->winner) {
                    $wUser = $draw->winner;
                    $winnerInfo = [
                        'id' => (int) $wUser->id,
                        'name' => $wUser->name,
                        'place' => !empty($wUser->place) ? $wUser->place : (!empty($wUser->locality) ? $wUser->locality : (!empty($wUser->territory) ? $wUser->territory : '')),
                        'whatsapp_number' => $wUser->whatsapp_number,
                        'locality' => $wUser->locality ?? '',
                        'territory' => $wUser->territory ?? '',
                    ];
                }

                $luckyDrawData = [
                    'id' => (int) $draw->id,
                    'status' => $draw->status,
                    'winner_user_id' => $draw->winner_user_id ? (int) $draw->winner_user_id : null,
                    'winner' => $winnerInfo,
                    'drawn_at' => $draw->drawn_at?->toISOString(),
                    'published_at' => $draw->published_at?->toISOString(),
                ];
            }
        }

        $adminUser = Auth::guard('admin')->user();

        return [
            'weeks' => $weeksSummary,
            'currentWeek' => $currentWeek ? [
                'id' => (int) $currentWeek->id,
                'week_number' => (int) $currentWeek->week_number,
                'quiz_date' => $currentWeek->quiz_date ? Carbon::parse($currentWeek->quiz_date)->format('d M Y') : '—',
                'status' => $currentWeek->status,
            ] : null,
            'eligibleParticipants' => $eligibleParticipants,
            'luckyDraw' => $luckyDrawData,
            'adminUser' => $adminUser ? $adminUser->name : 'Administrator',
        ];
    }
}
