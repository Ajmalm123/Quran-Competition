<?php

namespace App\Filament\Resources\QuizLeaderboardResource\Pages;

use App\Filament\Resources\QuizLeaderboardResource;
use Filament\Resources\Pages\ListRecords;

class ListQuizLeaderboards extends ListRecords
{
    protected static string $resource = QuizLeaderboardResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
