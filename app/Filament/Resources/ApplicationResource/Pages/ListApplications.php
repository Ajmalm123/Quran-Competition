<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Filament\Actions;
use App\Mail\BulkMail;
use App\Models\Application;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ApplicationResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Notifications\Notification;



class ListApplications extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';
    public $defaultAction = 'newApplicationsToday';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendMail')
                ->label('Send Mail')
                ->icon('heroicon-o-envelope')
                ->color('success')
                ->tooltip('Send emails to selected or filtered applications')
                ->button()
                ->action(function (array $data, $livewire) {
                    $selectedRecords = $livewire->getSelectedTableRecords();
                    $filteredRecords = $livewire->getFilteredTableQuery()->get();
                    
                    $recipients = $selectedRecords->isNotEmpty() ? $selectedRecords : $filteredRecords;
                    
                    foreach ($recipients as $application) {
                        $mailData = [
                            'applicant_name' => $application->full_name,
                            'zone' => $application->zone->name ?? 'N/A',
                            'center_name' => $application->zone->assignment->center_name ?? 'N/A',
                            'center_code' => $application->zone->assignment->center_code ?? 'N/A',
                            'location' => $application->zone->assignment->location ?? 'N/A',
                            'date' => $application->zone->assignment->date ?? 'N/A',
                            'reporting_time' => $application->zone->assignment->time ?? 'N/A',
                        ];

                        Mail::to($application->email)->queue(new BulkMail($mailData));
                    }

                    Notification::make()
                        ->title('Emails Queued Successfully')
                        ->body('All selected emails have been queued for sending.')
                        ->icon('heroicon-o-check-circle')
                        ->iconColor('success')
                        ->duration(5000)
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Confirm Email Send')
                ->modalDescription('Are you sure you want to send emails to all selected or filtered applications?')
                ->modalSubmitActionLabel('Yes, Send Emails')
                ->modalCancelActionLabel('Cancel'),
            Actions\CreateAction::make()
                ->label('Create Application')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function newApplicationsToday(): Actions\Action
    {
        $sessionKey = 'new_applications_modal_shown';
        $count = Session::get($sessionKey, 0);

        if ($count < 3) {
            Session::put($sessionKey, $count + 1);
            $newApplicationsToday = Application::query()->whereDate('created_at', today())->count();

            return Actions\Action::make('newApplicationsToday')
                ->visible($newApplicationsToday > 0)
                ->modalSubmitActionLabel('Got It!')
                ->color('success')
                ->modalCancelAction(false)
                ->modalHeading('New Applications Today')
                ->modalDescription(new HtmlString("Today so far got <strong>{$newApplicationsToday}</strong> new applications."))
                ->modalWidth('lg')
                ->action(null);
        }

        return Actions\Action::make('newApplicationsToday')->visible(false);
    }


}
