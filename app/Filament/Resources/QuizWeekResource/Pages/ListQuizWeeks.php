<?php

namespace App\Filament\Resources\QuizWeekResource\Pages;

use App\Filament\Resources\QuizWeekResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuizWeeks extends ListRecords
{
    protected static string $resource = QuizWeekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Schedule New Week')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
