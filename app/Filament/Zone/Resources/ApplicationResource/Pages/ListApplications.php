<?php

namespace App\Filament\Zone\Resources\ApplicationResource\Pages;

use App\Filament\Zone\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';


    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
