<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use App\Models\User;
use App\Services\QuizService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WeeklyQuizController extends Controller
{
    public function __construct(protected QuizService $quizService)
    {
    }

    /**
     * Public page view for Friday Night Quiz.
     * Pre-renders schedule and leaderboard data for lightning-fast initial paint.
     */
    public function index(Request $request): View
    {
        $user = Auth::guard('web')->user();

        $schedule = $this->quizService->getPublicSchedule();
        $leaderboard = $this->quizService->getLeaderboard($user);

        $currentUser = null;
        if ($user) {
            $currentUser = [
                'id' => (int) $user->id,
                'name' => $user->name,
                'territory' => $user->territory ?? $user->locality ?? 'Contestant',
                'locality' => $user->locality,
                'phone' => $user->whatsapp_number,
            ];
        }

        return view('quiz.index', [
            'weeks' => $schedule,
            'leaderboard' => $leaderboard,
            'currentUser' => $currentUser,
            'csrfToken' => csrf_token(),
            'demoCode' => app()->environment('local', 'testing') ? '123456' : '',
        ]);
    }

    /**
     * Get weekly schedule list.
     */
    public function schedule(): JsonResponse
    {
        return response()->json($this->quizService->getPublicSchedule());
    }

    /**
     * Get cumulative points leaderboard.
     */
    public function leaderboard(Request $request): JsonResponse
    {
        $user = Auth::guard('web')->user();
        return response()->json($this->quizService->getLeaderboard($user));
    }

    /**
     * Start or resume quiz attempt for currently authenticated user.
     */
    public function startQuiz(Request $request): JsonResponse
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json([
                'code' => 'UNAUTHORIZED',
                'message' => 'Please register or log in to take the quiz.',
            ], 401);
        }

        // Find currently live quiz week
        $allWeeks = QuizWeek::whereIn('status', [QuizWeek::STATUS_LIVE, QuizWeek::STATUS_UPCOMING])->get();
        $liveWeek = $allWeeks->first(fn (QuizWeek $w) => $w->isLive());

        if (!$liveWeek) {
            return response()->json([
                'code' => 'CLOSED',
                'message' => 'The quiz is closed right now.',
            ], 400);
        }

        try {
            $attempt = $this->quizService->getOrStartAttempt($user, $liveWeek);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            if (str_contains(strtolower($msg), 'already participated')) {
                return response()->json([
                    'code' => 'DONE',
                    'message' => "You have already played this week's quiz.",
                ], 400);
            }

            return response()->json([
                'code' => 'CLOSED',
                'message' => $msg,
            ], 400);
        }

        // Return the 3 assigned questions (NEVER send correct answers to frontend!)
        $questions = $attempt->attemptQuestions()
            ->with('question')
            ->orderBy('display_order')
            ->get()
            ->map(function ($aq) {
                $q = $aq->question;
                return [
                    'id' => (int) $q->id,
                    'text' => $q->question_text,
                    'options' => [
                        $q->option_a,
                        $q->option_b,
                        $q->option_c,
                        $q->option_d,
                    ],
                ];
            });

        return response()->json([
            'week' => (int) $liveWeek->week_number,
            'week_id' => (int) $liveWeek->id,
            'attempt_id' => (int) $attempt->id,
            'questions' => $questions,
        ]);
    }

    /**
     * Submit answers for the active quiz attempt.
     */
    public function submitQuiz(Request $request): JsonResponse
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json([
                'code' => 'UNAUTHORIZED',
                'message' => 'Please register or log in to submit your quiz.',
            ], 401);
        }

        $rawAnswers = $request->input('answers', []);

        // Find active in-progress attempt for user
        $attempt = QuizAttempt::where('user_id', $user->id)
            ->where('status', QuizAttempt::STATUS_IN_PROGRESS)
            ->latest()
            ->first();

        if (!$attempt) {
            // Check if already completed attempt exists
            $completed = QuizAttempt::where('user_id', $user->id)
                ->where('status', QuizAttempt::STATUS_COMPLETED)
                ->latest()
                ->first();

            if ($completed) {
                return response()->json($this->quizService->formatAttemptResult($completed));
            }

            return response()->json([
                'code' => 'NOTFOUND',
                'message' => 'No active quiz attempt found.',
            ], 404);
        }

        // Ensure week is still live or allow reasonable submission grace
        if (!$attempt->quizWeek->isLive()) {
            // Still allow submission if started during live window and submitted within 15 minutes of end
            if ($attempt->quizWeek->end_at->addMinutes(15)->lt(now())) {
                return response()->json([
                    'code' => 'CLOSED',
                    'message' => 'The quiz submission window has closed.',
                ], 400);
            }
        }

        // Map answer indices (0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D') to question IDs
        $assigned = $attempt->attemptQuestions()->orderBy('display_order')->get();
        $optionMap = [0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D'];
        $formattedAnswers = [];

        foreach ($assigned as $idx => $aq) {
            $qId = $aq->question_id;
            $val = $rawAnswers[$idx] ?? $rawAnswers[$qId] ?? null;

            if (is_numeric($val) && isset($optionMap[(int) $val])) {
                $formattedAnswers[$qId] = $optionMap[(int) $val];
            } elseif (is_string($val) && in_array(strtoupper(trim($val)), ['A', 'B', 'C', 'D'])) {
                $formattedAnswers[$qId] = strtoupper(trim($val));
            } else {
                $formattedAnswers[$qId] = '';
            }
        }

        try {
            $completedAttempt = $this->quizService->submitAttempt($attempt, $formattedAnswers);
            $result = $this->quizService->formatAttemptResult($completedAttempt);
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'code' => 'ERROR',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get result of user's attempt for a specific week.
     */
    public function result(Request $request, int $weekNumber): JsonResponse
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['code' => 'UNAUTHORIZED'], 401);
        }

        $week = QuizWeek::where('week_number', $weekNumber)->first();
        if (!$week) {
            return response()->json(['code' => 'NOTFOUND'], 404);
        }

        $attempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_week_id', $week->id)
            ->where('status', QuizAttempt::STATUS_COMPLETED)
            ->first();

        if (!$attempt) {
            return response()->json(['code' => 'NOTFOUND'], 404);
        }

        return response()->json($this->quizService->formatAttemptResult($attempt));
    }
}
