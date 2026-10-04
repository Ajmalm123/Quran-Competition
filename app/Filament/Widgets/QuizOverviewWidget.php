<?php

namespace App\Filament\Widgets;

use App\Models\LuckyDraw;
use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuizOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // 1. Current / Latest Quiz Week
        $currentWeek = QuizWeek::where('status', QuizWeek::STATUS_LIVE)
            ->orWhere(function ($q) {
                $now = Carbon::now();
                $q->where('start_at', '<=', $now)->where('end_at', '>=', $now);
            })
            ->first();

        if (!$currentWeek) {
            $currentWeek = QuizWeek::orderByDesc('week_number')->first();
        }

        $weekLabel = $currentWeek ? "Week {$currentWeek->week_number}" : 'No Quizzes';
        $weekStatus = $currentWeek ? ucfirst($currentWeek->computed_status) : 'Inactive';
        $weekColor = match ($currentWeek?->computed_status) {
            QuizWeek::STATUS_LIVE => 'success',
            QuizWeek::STATUS_UPCOMING => 'info',
            QuizWeek::STATUS_COMPLETED => 'warning',
            default => 'gray',
        };

        // 2. Total registered users
        $totalUsers = User::count();
        $verifiedUsers = User::where('whatsapp_verified', true)->count();

        // 3. Current week attempts and 30/30 count
        $attemptsCount = 0;
        $perfectCount = 0;
        if ($currentWeek) {
            $attemptsCount = QuizAttempt::where('quiz_week_id', $currentWeek->id)->count();
            $perfectCount = QuizAttempt::where('quiz_week_id', $currentWeek->id)
                ->where('weekly_score', 30)
                ->where('status', QuizAttempt::STATUS_COMPLETED)
                ->count();
        }

        // 4. Latest published winner
        $latestDraw = LuckyDraw::whereNotNull('winner_user_id')
            ->whereIn('status', [LuckyDraw::STATUS_CONFIRMED, LuckyDraw::STATUS_PUBLISHED])
            ->latest('published_at')
            ->first();

        $winnerName = $latestDraw?->winner?->name ?? 'None yet';
        $winnerWeek = $latestDraw ? "Week {$latestDraw->quizWeek->week_number}" : '';

        return [
            Stat::make('Current Quiz Week', "{$weekLabel} ({$weekStatus})")
                ->description($currentWeek ? $currentWeek->quiz_date->format('D, d M Y') : 'Schedule a week')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($weekColor),

            Stat::make('Quiz Participants', number_format($totalUsers))
                ->description("{$verifiedUsers} WhatsApp verified")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Weekly Attempts', number_format($attemptsCount))
                ->description("{$perfectCount} participants achieved 30/30")
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('info'),

            Stat::make('Latest Lucky Draw Winner', $winnerName)
                ->description($winnerWeek ? "{$winnerWeek} Winner" : 'Awaiting completed quiz')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning'),
        ];
    }
}
