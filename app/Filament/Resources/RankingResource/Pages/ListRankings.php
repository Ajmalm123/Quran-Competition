<?php

namespace App\Filament\Resources\RankingResource\Pages;

use App\Filament\Resources\RankingResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListRankings extends ListRecords
{
    protected static string $resource = RankingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // You can add actions here if needed
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // You can add widgets here if needed
        ];
    }
}