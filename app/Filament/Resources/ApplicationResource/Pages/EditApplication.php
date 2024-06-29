<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplication extends EditRecord
{
    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = '7xl';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
