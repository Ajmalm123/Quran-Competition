<?php

namespace App\Filament\Zone\Resources\ParticipantsResource\Pages;

use App\Filament\Zone\Resources\ParticipantsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantsResource::class;
    protected ?string $maxContentWidth = 'full';


    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
