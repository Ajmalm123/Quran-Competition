<?php

namespace App\Filament\Resources\QuizWeekResource\Pages;

use App\Filament\Resources\QuizWeekResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuizWeek extends ViewRecord
{
    protected static string $resource = QuizWeekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
