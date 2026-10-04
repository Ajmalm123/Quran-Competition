<?php

namespace App\Filament\Resources\QuizWeekResource\Pages;

use App\Filament\Resources\QuizWeekResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuizWeek extends CreateRecord
{
    protected static string $resource = QuizWeekResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
