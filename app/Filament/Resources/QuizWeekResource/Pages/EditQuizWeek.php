<?php

namespace App\Filament\Resources\QuizWeekResource\Pages;

use App\Filament\Resources\QuizWeekResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuizWeek extends EditRecord
{
    protected static string $resource = QuizWeekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
