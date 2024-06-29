<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ApplicationResource;
use App\Models\Application;
use Filament\Notifications\Notification;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->requiresConfirmation()
                ->color('success')
                ->action(function (Model $record) {
                    $record->status = Application::STATUS['Approved'];
                    $record->save();
                    Notification::make()->title('Application Approved')->success()->send();
                })
                ->hidden(function (Application $application) {
                    return  $application->status == 'Approved' || $application->status == 'Rejected';
                }),
            Actions\Action::make('reject')
                ->requiresConfirmation()
                ->color('danger')
                ->action(function (Model $record) {
                    $record->status = Application::STATUS['Rejected'];
                    $record->save();
                    Notification::make()->title('Application Rejected')->success()->send();
                })->hidden(function (Application $application) {
                    return  $application->status == 'Rejected' || $application->status == 'Approved';
                }),
            Actions\EditAction::make()->color('info'),
            Actions\DeleteAction::make(),
        ];
    }
}
