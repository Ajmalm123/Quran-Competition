<?php

namespace App\Filament\Resources\ParticipantsResource\Pages;

use Filament\Actions;
use App\Jobs\SendEmailJob;
use App\Models\Application;
use Filament\Actions\StaticAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;
use App\Filament\Resources\ApplicationResource;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewParticipants extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';

}