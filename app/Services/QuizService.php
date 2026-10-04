<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\AttemptQuestion;
use App\Models\LuckyDraw;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use App\Models\User;
use App\Models\WeeklyScore;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class QuizService
{
    /**
     * Start a quiz attempt for a user on a given week.
     * Enforces one attempt per week and randomly selects 3 questions from the 6-question pool.
     */
    public function startAttempt(User $user, QuizWeek $quizWeek): QuizAttempt
    {
        // Check if user already has an attempt
        $existing = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_week_id', $quizWeek->id)
            ->first();

        if ($existing) {
            throw new Exception("You have already participated in this week's quiz.");
        }

        // Check if quiz is currently live
        if (!$quizWeek->isLive()) {
            throw new Exception("The quiz for Week {$quizWeek->week_number} is not currently live.");
        }

        // Verify that 6 questions exist
        $allQuestions = $quizWeek->questions()->get();
        if ($allQuestions->count() < 6) {
            throw new Exception("The weekly quiz question pool is not fully configured (6 questions required).");
        }

        return DB::transaction(function () use ($user, $quizWeek, $allQuestions) {
            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_week_id' => $quizWeek->id,
                'started_at' => Carbon::now(),
                'status' => QuizAttempt::STATUS_IN_PROGRESS,
                'weekly_score' => 0,
            ]);

            // Randomly select 3 questions from the 6
            $randomQuestions = $allQuestions->random(3);

            $order = 1;
            foreach ($randomQuestions as $question) {
                AttemptQuestion::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'display_order' => $order++,
                ]);
            }

            return $attempt;
        });
    }

    /**
     * Submit answers for an attempt and compute score.
     *
     * @param QuizAttempt $attempt
     * @param array<int, string> $submittedAnswers [question_id => selected_option (A/B/C/D)]
     * @return QuizAttempt
     */
    public function submitAttempt(QuizAttempt $attempt, array $submittedAnswers): QuizAttempt
    {
        if ($attempt->status === QuizAttempt::STATUS_COMPLETED) {
            throw new Exception("This quiz attempt has already been submitted and completed.");
        }

        $assignedQuestionIds = $attempt->attemptQuestions()->pluck('question_id')->toArray();

        return DB::transaction(function () use ($attempt, $submittedAnswers, $assignedQuestionIds) {
            $totalScore = 0;
            $now = Carbon::now();

            foreach ($assignedQuestionIds as $questionId) {
                $question = Question::find($questionId);
                $selectedOption = strtoupper(trim($submittedAnswers[$questionId] ?? ''));

                $isCorrect = ($selectedOption === strtoupper($question->correct_option));
                if ($isCorrect) {
                    $totalScore += 10;
                }

                Answer::updateOrCreate(
                    [
                        'attempt_id' => $attempt->id,
                        'question_id' => $questionId,
                    ],
                    [
                        'selected_option' => in_array($selectedOption, ['A', 'B', 'C', 'D']) ? $selectedOption : null,
                        'is_correct' => $isCorrect,
                        'answered_at' => $now,
                    ]
                );
            }

            // Cap at 30
            $totalScore = min(30, max(0, $totalScore));

            $attempt->update([
                'status' => QuizAttempt::STATUS_COMPLETED,
                'submitted_at' => $now,
                'weekly_score' => $totalScore,
            ]);

            // Update or create weekly score entry
            WeeklyScore::updateOrCreate(
                [
                    'user_id' => $attempt->user_id,
                    'quiz_week_id' => $attempt->quiz_week_id,
                ],
                [
                    'score' => $totalScore,
                ]
            );

            \Illuminate\Support\Facades\Cache::forget('quiz_leaderboard_top10');

            return $attempt->fresh();
        });
    }

    /**
     * Get all eligible users for the lucky draw of a quiz week.
     * Rule: weekly_score = 30 AND status = completed.
     */
    public function getEligibleLuckyDrawParticipants(QuizWeek $quizWeek)
    {
        return User::whereHas('quizAttempts', function ($q) use ($quizWeek) {
            $q->where('quiz_week_id', $quizWeek->id)
                ->where('status', QuizAttempt::STATUS_COMPLETED)
                ->where('weekly_score', 30);
        })->get();
    }

    /**
     * Execute Lucky Draw for a quiz week: randomly selects one winner from 30/30 participants.
     */
    public function executeLuckyDraw(QuizWeek $quizWeek, ?string $notes = null): LuckyDraw
    {
        $eligibleUsers = $this->getEligibleLuckyDrawParticipants($quizWeek);

        if ($eligibleUsers->isEmpty()) {
            throw new Exception("No participants achieved a perfect score of 30/30 for Week {$quizWeek->week_number}.");
        }

        $winner = $eligibleUsers->random();

        $luckyDraw = LuckyDraw::updateOrCreate(
            ['quiz_week_id' => $quizWeek->id],
            [
                'winner_user_id' => $winner->id,
                'status' => LuckyDraw::STATUS_DRAWN,
                'drawn_at' => Carbon::now(),
                'notes' => $notes,
            ]
        );

        return $luckyDraw;
    }

    /**
     * Confirm lucky draw winner (locks against accidental changes).
     */
    public function confirmWinner(LuckyDraw $luckyDraw): LuckyDraw
    {
        if (empty($luckyDraw->winner_user_id)) {
            throw new Exception("Cannot confirm lucky draw without a selected winner.");
        }

        $luckyDraw->update([
            'status' => LuckyDraw::STATUS_CONFIRMED,
            'confirmed_at' => Carbon::now(),
        ]);

        return $luckyDraw;
    }

    /**
     * Publish lucky draw winner (makes visible on public website).
     */
    public function publishWinner(LuckyDraw $luckyDraw): LuckyDraw
    {
        if (empty($luckyDraw->winner_user_id)) {
            throw new Exception("Cannot publish lucky draw without a winner.");
        }

        $luckyDraw->update([
            'status' => LuckyDraw::STATUS_PUBLISHED,
            'published_at' => Carbon::now(),
        ]);

        \Illuminate\Support\Facades\Cache::forget('quiz_public_schedule');

        return $luckyDraw;
    }

    /**
     * Start or resume an in-progress attempt for a user on a given week.
     */
    public function getOrStartAttempt(User $user, QuizWeek $quizWeek): QuizAttempt
    {
        $existing = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_week_id', $quizWeek->id)
            ->first();

        if ($existing) {
            if ($existing->status === QuizAttempt::STATUS_COMPLETED) {
                throw new Exception("You have already participated in this week's quiz.", 400);
            }
            return $existing;
        }

        return $this->startAttempt($user, $quizWeek);
    }

    /**
     * Format completed attempt result for the frontend.
     */
    public function formatAttemptResult(QuizAttempt $attempt): array
    {
        $answers = $attempt->answers()->get()->keyBy('question_id');
        $assignedQuestions = $attempt->attemptQuestions()->orderBy('display_order')->get();

        $correct = [];
        foreach ($assignedQuestions as $aq) {
            $ans = $answers->get($aq->question_id);
            $correct[] = (bool) ($ans?->is_correct ?? false);
        }

        $user = $attempt->user;
        $totalPoints = (int) WeeklyScore::where('user_id', $user->id)->sum('score');

        return [
            'score' => (int) $attempt->weekly_score,
            'correct' => $correct,
            'total' => $totalPoints,
            'eligible' => (bool) ($attempt->weekly_score === 30 && $attempt->status === QuizAttempt::STATUS_COMPLETED),
        ];
    }

    /**
     * Get public weekly schedule list with winner information.
     */
    public function getPublicSchedule(): array
    {
        return \Illuminate\Support\Facades\Cache::remember('quiz_public_schedule', 60, function () {
            $weeks = QuizWeek::with(['luckyDraw.winner'])
                ->orderBy('week_number', 'asc')
                ->get();

            return $weeks->map(function (QuizWeek $w) {
                $winner = null;
                if ($w->luckyDraw && $w->luckyDraw->isPublished() && $w->luckyDraw->winner) {
                    $winner = [
                        'id' => $w->luckyDraw->winner->id,
                        'name' => $w->luckyDraw->winner->name,
                        'territory' => $w->luckyDraw->winner->territory ?? $w->luckyDraw->winner->locality ?? 'Contestant',
                    ];
                }

                return [
                    'id' => (int) $w->week_number,
                    'week_id' => (int) $w->id,
                    'start' => (int) ($w->start_at->timestamp * 1000),
                    'end' => (int) ($w->end_at->timestamp * 1000),
                    'status' => $w->computed_status,
                    'winner' => $winner,
                ];
            })->toArray();
        });
    }

    /**
     * Get cumulative points leaderboard data (top 10 + current user).
     */
    public function getLeaderboard(?User $user = null): array
    {
        $top = \Illuminate\Support\Facades\Cache::remember('quiz_leaderboard_top10', 30, function () {
            $topUsers = User::query()
                ->select('users.id', 'users.name', 'users.locality', 'users.territory')
                ->join('weekly_scores', 'users.id', '=', 'weekly_scores.user_id')
                ->groupBy('users.id', 'users.name', 'users.locality', 'users.territory')
                ->selectRaw('CAST(SUM(weekly_scores.score) AS UNSIGNED) as total_points')
                ->having('total_points', '>', 0)
                ->orderByDesc('total_points')
                ->orderBy('users.id', 'asc')
                ->limit(10)
                ->get();

            $list = [];
            $rank = 1;
            foreach ($topUsers as $u) {
                $list[] = [
                    'id' => (int) $u->id,
                    'name' => $u->name,
                    'territory' => $u->territory ?? $u->locality ?? 'Contestant',
                    'points' => (int) $u->total_points,
                    'rank' => $rank++,
                ];
            }

            return $list;
        });

        $me = null;
        if ($user) {
            $foundInTop = collect($top)->firstWhere('id', $user->id);
            if ($foundInTop) {
                $me = $foundInTop;
            } else {
                $userPoints = (int) WeeklyScore::where('user_id', $user->id)->sum('score');
                if ($userPoints > 0) {
                    $higherCount = DB::table('weekly_scores')
                        ->select('user_id', DB::raw('SUM(score) as total'))
                        ->groupBy('user_id')
                        ->having('total', '>', $userPoints)
                        ->get()
                        ->count();

                    $me = [
                        'id' => (int) $user->id,
                        'name' => $user->name,
                        'territory' => $user->territory ?? $user->locality ?? 'Contestant',
                        'points' => $userPoints,
                        'rank' => $higherCount + 1,
                    ];
                }
            }
        }

        return [
            'top' => $top,
            'me' => $me,
        ];
    }
}

