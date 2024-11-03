<?php

namespace App\Filament\Resources\RankingResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RankingResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListRankings extends ListRecords
{

    protected ?string $maxContentWidth = 'full';

    protected static string $resource = RankingResource::class;
}