<?php

namespace App\Http\Controllers;

use App\Models\TibyanDemoAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TibyanController extends Controller
{
    /**
     * Display the Tibiyan coming soon page.
     */
    public function index(): View
    {
        return view('tibyan');
    }

    /**
     * Record a demo quiz attempt from an anonymous visitor.
     */
    public function recordDemoAttempt(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'selected_option' => ['required', 'string', 'in:A,B,C,D,a,b,c,d'],
            'visitor_id' => ['nullable', 'string', 'max:100'],
        ]);

        $option = strtoupper($validated['selected_option']);
        $isCorrect = ($option === 'B');

        $attempt = TibyanDemoAttempt::create([
            'visitor_id' => $validated['visitor_id'] ?? null,
            'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            'selected_option' => $option,
            'is_correct' => $isCorrect,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return response()->json([
            'success' => true,
            'attempt_id' => $attempt->id,
            'is_correct' => $isCorrect,
        ]);
    }

    /**
     * Retrieve aggregated statistics for demo quiz attendance.
     */
    public function getDemoStats(): JsonResponse
    {
        $totalAttempts = TibyanDemoAttempt::count();

        // Unique visitors calculated primarily by unique visitor_id, or distinct IPs as fallback
        $uniqueVisitorsById = TibyanDemoAttempt::whereNotNull('visitor_id')->distinct('visitor_id')->count('visitor_id');
        $uniqueVisitorsByIp = TibyanDemoAttempt::whereNotNull('ip_address')->distinct('ip_address')->count('ip_address');
        $uniqueVisitors = max($uniqueVisitorsById, $uniqueVisitorsByIp);

        $correct = TibyanDemoAttempt::where('is_correct', true)->count();
        $incorrect = TibyanDemoAttempt::where('is_correct', false)->count();

        $breakdown = TibyanDemoAttempt::selectRaw('selected_option, count(*) as count')
            ->groupBy('selected_option')
            ->pluck('count', 'selected_option')
            ->all();

        return response()->json([
            'success' => true,
            'total_attempts' => $totalAttempts,
            'unique_visitors' => $uniqueVisitors,
            'correct' => $correct,
            'incorrect' => $incorrect,
            'accuracy_rate' => $totalAttempts > 0 ? round(($correct / $totalAttempts) * 100, 1) . '%' : '0%',
            'breakdown' => [
                'A' => $breakdown['A'] ?? 0,
                'B' => $breakdown['B'] ?? 0,
                'C' => $breakdown['C'] ?? 0,
                'D' => $breakdown['D'] ?? 0,
            ],
        ]);
    }
}
