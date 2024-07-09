<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Filament\Actions;
use App\Jobs\SendEmailJob;
use App\Models\Application;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Actions\MountableAction;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconPosition;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\ApplicationResource;

class ViewApplication extends ViewRecord
{

    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('status')
            ->label(function (Application $record) {
                return ucfirst($record->status);
            })
            ->badge()
            ->icon(function (Application $record) {
                return match ($record->status) {
                    Application::STATUS['Approved'] => 'heroicon-o-check-circle',
                    Application::STATUS['Rejected'] => 'heroicon-o-x-circle',
                    Application::STATUS['withheld'] => 'heroicon-o-clock',
                    Application::STATUS['Created'] => 'heroicon-o-document',
                    default => 'heroicon-o-question-mark-circle',
                };
            })
            ->iconPosition(IconPosition::Before)
            ->color(function (Application $record) {
                return match ($record->status) {
                    Application::STATUS['Approved'] => 'success',
                    Application::STATUS['Rejected'] => 'danger',
                    Application::STATUS['withheld'] => 'warning',
                    Application::STATUS['Created'] => 'gray',
                    default => 'info',
                };
            })
            ->extraAttributes([
                'class' => 'font-medium text-sm px-3 py-1.5 rounded-full',
            ])
            ->disabled(),
            Actions\Action::make('approve')
                ->requiresConfirmation()
                ->color('success')
                ->action(function (Model $record) {
                    $record->status = Application::STATUS['Approved'];
                    $record->save();
                    $dispatchData = [
                        'page' => 'emails.application',
                        'application' => $record,
                        'subject' => 'Application Approved',
                        'message' => 'We are pleased to inform you that your application has been approved.',
                    ];

                    // Dispatch the job
                    SendEmailJob::dispatch($dispatchData);
                    Notification::make()->success()->title('Application Approved')->send();
                })
                ->hidden(function (Application $application) {
                    return $application->status == 'Approved' || $application->status == 'Rejected';
                }),
            Actions\Action::make('reject')
                ->requiresConfirmation()
                ->color('danger')
                ->action(function (Model $record) {
                    $record->status = Application::STATUS['Rejected'];
                    $record->save();
                    $dispatchData = [
                        'page' => 'emails.application',
                        'application' => $record,
                        'subject' => 'Application Rejected',
                        'message' => 'We regret to inform you that your application has been rejected.',
                    ];
                    // Dispatch the job
                    SendEmailJob::dispatch($dispatchData);
                    Notification::make()->title('Application Rejected')->success()->send();
                })->hidden(function (Application $application) {
                    return $application->status == 'Rejected' || $application->status == 'Approved';
                }),
            Actions\EditAction::make()->color('info'),
            Actions\DeleteAction::make()->icon('heroicon-o-trash')->defaultView(StaticAction::LINK_VIEW),
        ];
    }

    // public function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Section::make('Application Details')
    //                 ->schema([
    //                     Grid::make(3)->schema([
    //                         ImageEntry::make('passport_size_photo'),
    //                         TextEntry::make('full_name'),
    //                         TextEntry::make('date_of_birth'),
    //                     ]),
    //                     Grid::make(3)->schema([
    //                         TextEntry::make('educational_qualification'),
    //                         TextEntry::make('aadhar_number'),
    //                         TextEntry::make('gender'),
    //                     ]),

    //                 ]),
    //             Section::make('Contact Information')
    //                 ->schema([
    //                     Grid::make(3)->schema([
    //                         TextEntry::make('contact_number'),
    //                         TextEntry::make('whatsapp'),
    //                         TextEntry::make('email'),
    //                     ]),
    //                     Grid::make(3)->schema([
    //                         TextEntry::make('c_address')->label('Current Address'),
    //                         TextEntry::make('pr_address')->label('Permanent Address'),
    //                         TextEntry::make('pincode'),
    //                     ]),

    //                     Grid::make(3)->schema([
    //                         TextEntry::make('district'),
    //                     ]),

    //                 ]),

    //             Section::make('Hifz and Participation Details')
    //                 ->schema([
    //                     Grid::make(3)->schema([
    //                         TextEntry::make('institution_name'),
    //                         TextEntry::make('is_completed_ijazah'),
    //                         TextEntry::make('qirath_with_ijazah'),
    //                     ]),
    //                     Grid::make(3)->schema([
    //                         TextEntry::make('primary_competition_participation'),
    //                         TextEntry::make('zone'),
    //                         TextEntry::make('status')
    //                             ->badge()
    //                             ->color(fn(string $state): string => match ($state) {
    //                                 'Created' => 'grey',
    //                                 'withheld' => 'warning',
    //                                 'Approved' => 'success',
    //                                 'Rejected' => 'danger'
    //                             })
    //                     ]),


    //                 ]),
    //             Section::make('Documents')
    //                 ->schema([
    //                     Grid::make(3)->schema([
    //                         ImageEntry::make('birth_certificate'),
    //                         ImageEntry::make('letter_of_recommendation'),
    //                     ]),

    //                 ]),

    //         ]);
    // }
}
