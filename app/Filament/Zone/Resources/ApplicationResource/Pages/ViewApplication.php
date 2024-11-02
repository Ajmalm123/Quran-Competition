<?php

namespace App\Filament\Zone\Resources\ApplicationResource\Pages;

use Filament\Actions;
use App\Jobs\SendEmailJob;
use App\Models\Application;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;
use App\Filament\Resources\ApplicationResource;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('admit_status')
                ->label(fn(Application $record) => ucfirst($record->admit_status))
                ->badge()
                ->icon(fn(Application $record) => match ($record->admit_status) {
                    'Admitted' => 'heroicon-o-check-circle',
                    'Declined' => 'heroicon-o-x-circle',
                    'Absent' => 'heroicon-o-x-mark',
                    'Pending' => 'heroicon-o-clock',
                    default => 'heroicon-o-question-mark-circle',
                })
                ->iconPosition(IconPosition::Before)
                ->color(fn(Application $record) => match ($record->admit_status) {
                    'Admitted' => 'success',
                    'Declined' => 'danger',
                    'Absent' => 'gray',
                    'Pending' => 'warning',
                    default => 'info',
                })
                ->extraAttributes([
                    'class' => 'font-medium text-sm px-3 py-1.5 rounded-full',
                ])
                ->disabled(),

            Actions\Action::make('admit')
                ->label('Admit')
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->form([
                    TextInput::make('token_number')
                        ->label('Token Number')
                        ->placeholder('Enter token number'),
                ])
                ->action(function (array $data, Application $record) {
                    $record->admit_status = 'Admitted';
                    $record->token_number = $data['token_number'];
                    $record->save();
                    Notification::make()->success()->title('Application Admitted')->body("Token number: {$data['token_number']}")->send();
                })
                ->hidden(fn(Application $application) => $application->admit_status == 'Declined'|| $application->admit_status == 'Admitted'|| $application->admit_status =='Absent'|| $application->admit_status == 'Completed'),

            Actions\Action::make('decline')
                ->label('Decline')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (Application $record) {
                    $record->admit_status = 'Declined';
                    $record->save();
                    Notification::make()->success()->title('Application Declined')->send();
                })
                ->hidden(fn(Application $application) => $application->admit_status == 'Declined'||$application->admit_status == 'Admitted'||$application->admit_status =='Absent'|| $application->admit_status == 'Completed'),

            Actions\Action::make('absent')
                ->label('Absent')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->action(function (Application $record) {
                    $record->admit_status = 'Absent';
                    $record->save();
                    Notification::make()->success()->title('Application Marked as Absent')->send();
                })
                ->hidden(fn(Application $application) => $application->admit_status == 'Declined'||$application->admit_status == 'Admitted'||$application->admit_status =='Absent'),

            Actions\EditAction::make()->color('info'),
        ];
    }
}
