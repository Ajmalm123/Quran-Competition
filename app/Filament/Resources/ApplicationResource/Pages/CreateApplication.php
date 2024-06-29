<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateApplication extends CreateRecord
{
    protected ?string $maxContentWidth = '7xl';
    protected static string $resource = ApplicationResource::class;
}
