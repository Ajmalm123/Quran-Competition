<?php

namespace App\Filament\Resources\ZoneAssignmentResource\Pages;

use App\Filament\Resources\ZoneAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Section;

class EditZoneAssignment extends EditRecord
{
    protected static string $resource = ZoneAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return ZoneAssignmentResource::getUrl('index');
    }

  
}
