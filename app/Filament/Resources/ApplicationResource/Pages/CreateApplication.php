<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Actions;
use App\Jobs\SendEmailJob;
use App\Models\Application;
use Forms\Components\Wizard\Step;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ApplicationResource;

class CreateApplication extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected ?string $maxContentWidth = 'full';
    protected static string $resource = ApplicationResource::class;


    protected function getCreatedNotification(): Notification
    {
        return Notification::make()->title('New Application Created')->success()->duration(3000)->send();
    }
    protected function getRedirectUrl(): string
    {
        return ApplicationResource::getUrl('index');
    }

    protected function beforeCreate()
    {
    }

    protected function afterCreate()
    {
        $dispatchData = [
            'page' => 'emails.application-recieved',
            'application' => $this->record,
            'subject' => 'Application Received',
            'message' => 'Thank you for your application. We have received it and will review it shortly.',
        ];
        // Dispatch the job
        SendEmailJob::dispatch($dispatchData);
    }


    

    protected function getSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('Application Details')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\FileUpload::make('passport_size_photo')
                            ->disk('public')
                            ->directory('passport')
                            ->image()
                            ->imagePreviewHeight('220')
                            ->columnSpan(1),
                        Forms\Components\Grid::make(1)->schema([
                            Forms\Components\TextInput::make('full_name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('age')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->formatStateUsing(fn ($record) => $record?->date_of_birth ? Carbon::parse($record->date_of_birth)->age : null),
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->required(),
                                Forms\Components\Select::make('gender')
                                    ->required()
                                    ->options(Application::GENDER),
                            ]),
                            Forms\Components\Grid::make(4)->schema([
                                Forms\Components\Select::make('educational_qualification')
                                    ->required()
                                    ->options(Application::EDUCATION_QUALIFICATION),
                                Forms\Components\TextInput::make('job'),
                                Forms\Components\Select::make('mother_tongue')
                                    ->required()
                                    ->options(Application::MOTHERTONGUE),
                                Forms\Components\TextInput::make('aadhar_number')
                                    ->required()
                                    ->maxLength(12),
                            ]),
                        ])->columnSpan(2),
                    ]),
                ])
                ->icon('heroicon-o-users'),
            Forms\Components\Wizard\Step::make('Contact Information')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('contact_number')
                            ->required()
                            ->numeric()
                            ->maxLength(15),
                        TextInput::make('whatsapp')
                            ->numeric()
                            ->maxLength(15),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),
                    Grid::make(3)->schema([
                        Textarea::make('c_address')
                            ->label('Current Address')
                            ->required(),
                        // ->columnSpanFull(),
                        Textarea::make('pr_address')
                            ->label('Permanant Address')

                            ->required(),
                        // ->columnSpanFull(),
                        TextInput::make('pincode')
                            ->numeric()
                            ->maxLength(8),
                    ]),
                    Grid::make(3)->schema([
                        Select::make('district')
                            ->required()
                            ->options(Application::DISTRICT),
                    ])

                ])->icon('heroicon-o-identification'),
            Forms\Components\Wizard\Step::make('Hifz and Participation Details')
                ->schema([
                    Grid::make(3)->schema([
                        Textarea::make('institution_name'),
                        // ->columnSpanFull(),
                        Select::make('is_completed_ijazah')
                            ->required()
                            ->options(Application::IS_COMPLETED_IJAZAH),
                        Textarea::make('qirath_with_ijazah'),
                        // ->columnSpanFull(),
                    ]),
                    Grid::make(3)->schema([
                        Select::make('primary_competition_participation')
                            ->required()
                            ->options(Application::PRIMARY_COMPETITION_PARTICIPATION),
                        Select::make('zone')
                            ->required()
                            ->options(Application::ZONE),
                        Select::make('status')
                            ->required()
                            ->options(Application::STATUS),
                    ]),

                ])->icon('heroicon-o-academic-cap'),
            Forms\Components\Wizard\Step::make('Documents')
                ->schema([
                    Grid::make(3)->schema([
                        FileUpload::make('birth_certificate')
                            ->disk('public')
                            ->directory('birth-certificate')
                            ->openable(),
                        FileUpload::make('letter_of_recommendation')
                            ->disk('public')
                            ->directory('letter-of-recommendation')
                            ->openable(),
                    ]),

                ])->icon('heroicon-o-document'),
        ];
    }

    protected function hasSkippableSteps(): bool
    {
        return true;
    }
}
