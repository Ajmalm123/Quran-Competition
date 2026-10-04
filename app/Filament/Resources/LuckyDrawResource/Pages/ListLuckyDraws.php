<?php

namespace App\Filament\Resources\LuckyDrawResource\Pages;

use App\Filament\Resources\LuckyDrawResource;
use App\Models\LuckyDraw;
use App\Models\QuizWeek;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListLuckyDraws extends ListRecords
{
    protected static string $resource = LuckyDrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('syncDraws')
                ->label('Check Completed Weeks')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $completedWeeks = QuizWeek::where('status', QuizWeek::STATUS_COMPLETED)
                        ->orWhere('end_at', '<=', now())
                        ->get();

                    $created = 0;
                    foreach ($completedWeeks as $week) {
                        $draw = LuckyDraw::firstOrCreate(
                            ['quiz_week_id' => $week->id],
                            ['status' => LuckyDraw::STATUS_PENDING]
                        );
                        if ($draw->wasRecentlyCreated) {
                            $created++;
                        }
                    }

                    Notification::make()
                        ->success()
                        ->title('Lucky Draws Synchronized')
                        ->body("{$created} new weekly lucky draw entry initialized.")
                        ->send();
                }),

            Actions\CreateAction::make()
                ->label('New Lucky Draw')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
