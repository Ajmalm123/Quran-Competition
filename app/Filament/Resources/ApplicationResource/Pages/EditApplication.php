<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Filament\Forms;
use Filament\Actions;
use App\Models\Application;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ApplicationResource;


class EditApplication extends EditRecord
{
    use EditRecord\Concerns\HasWizard;

    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = '7xl';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return ApplicationResource::getUrl('index');
    }


    protected function getSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('Application Details')
                ->schema([
                    Grid::make(3)->schema([
                        FileUpload::make('passport_size_photo')
                            ->disk('public')
                            ->directory('passport')
                            ->openable(),
                        TextInput::make('full_name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        DatePicker::make('date_of_birth')
                            ->required()
                            ->columnSpan(1),

                        // ->columnSpan(1),
                    ]),
                    Grid::make(3)->schema([
                        Select::make('educational_qualification')
                            ->required()
                            ->options(Application::EDUCATION_QUALIFICATION),
                        // ->columnSpan(1),
                        TextInput::make('aadhar_number')
                            ->numeric()
                            ->required()
                            ->maxLength(12),
                        // ->columnSpan(1),
                        Select::make('gender')
                            ->required()
                            ->options(Application::GENDER),
                    ]),
                ])->icon('heroicon-o-users'),
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
