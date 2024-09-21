<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use Filament\Forms\Form;
use App\Jobs\SendEmailJob;
use Filament\Tables\Table;
use App\Models\Application;
use Filament\Resources\Resource;
use App\Exports\ApplicationExport;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\Actions\ExportPdfAction;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;
    protected static ?int $navigationSort = 2;



    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(3)->schema([
                    FileUpload::make('passport_size_photo')
                        ->disk('public')
                        ->directory('passport')
                        ->openable()
                        ->image()
                        ->imagePreviewHeight('220')
                        ->columnSpan(1),
                    Grid::make(1)->schema([
                        TextInput::make('full_name')
                            ->required()
                            ->maxLength(255),
                        Grid::make(3)->schema([
                            TextInput::make('age')
                                ->formatStateUsing(function ($record) {
                                    return Carbon::parse($record->date_of_birth)->age;
                                }),
                            DatePicker::make('date_of_birth')
                                ->required(),
                            Select::make('gender')
                                ->required()
                                ->options([
                                    Application::GENDER
                                ]),
                        ]),
                        Grid::make(4)->schema([
                            Select::make('educational_qualification')
                                ->required()
                                ->options([
                                    Application::EDUCATION_QUALIFICATION
                                ]),
                            TextInput::make('job'),
                            Select::make('mother_tongue')
                                ->required()
                                ->options([
                                    Application::MOTHERTONGUE
                                ]),
                            TextInput::make('aadhar_number')
                                ->required()
                                ->maxLength(12),
                        ]),
                    ])->columnSpan(2),
                ]),
            ]),

            Forms\Components\Section::make('Contact Information')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('contact_number')
                                ->label('Contact Number')
                                ->required()
                                ->maxLength(15),
                            TextInput::make('whatsapp')
                                ->label('WhatsApp')
                                ->maxLength(15),
                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                        ]),
                    Grid::make(3)
                        ->schema([
                            Textarea::make('c_address')
                                ->label('Current Address')
                                ->required()
                                ->columnSpan(1),
                            Textarea::make('pr_address')
                                ->label('Permanent Address')
                                ->required()
                                ->columnSpan(1),
                            Select::make('district')
                                ->label('District')
                                ->required()
                                ->options(Application::DISTRICT)
                                ->columnSpan(1),
                        ]),

                ])
                ->columns(1),

            Forms\Components\Section::make('Hifz and Participation Details')
                ->schema([
                    Textarea::make('institution_name')
                        ->required()
                        ->columnSpanFull(),
                    Grid::make(2)
                        ->schema([
                            Select::make('is_completed_ijazah')
                                ->required()
                                ->options(Application::IS_COMPLETED_IJAZAH),
                            Textarea::make('qirath_with_ijazah')->columnSpan(1),
                        ]),
                    Grid::make(3)
                        ->schema([
                            Select::make('primary_competition_participation')
                                ->required()
                                ->options(Application::PRIMARY_COMPETITION_PARTICIPATION),
                            Select::make('zone_id')
                                ->label('Zone')
                                ->required()
                                ->options(Zone::pluck('name', 'id')),
                        ]),
                ])
                ->columns(1),

            Forms\Components\Section::make('Documents')
                ->schema([
                    FileUpload::make('birth_certificate')
                        ->disk('public')
                        ->directory('birth-certificate')
                        ->openable(),
                    FileUpload::make('letter_of_recommendation')
                        ->disk('public')
                        ->directory('letter-of-recommendation')
                        ->openable(),
                ])
                ->columns(2),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('passport_size_photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->label('Photo')
                    ->width('50px'),
                TextColumn::make('application_id')
                    ->searchable()
                    ->copyable()
                    ->wrap(),
                TextColumn::make('full_name')
                    ->searchable()
                    ->weight('bold')
                    ->sortable()
                    ->wrap(),
                TextColumn::make('contact_number')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone')
                    ->wrap(),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->wrap(),
                // TextColumn::make('district')
                //     ->searchable()
                //     ->wrap(),
                TextColumn::make('zone.name')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('age')
                    ->label('Age')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('date_of_birth', $direction === 'desc' ? 'asc' : 'desc');
                    })
                    ->getStateUsing(fn($record) => Carbon::parse($record->date_of_birth)->age)
                    ->width('50px'),
                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'Created',
                        'warning' => 'Withheld',
                        'success' => 'Approved',
                        'danger' => 'Rejected'
                    ])
                    ->icons([
                        'heroicon-o-document' => 'Created',
                        'heroicon-o-clock' => 'Withheld',
                        'heroicon-o-check-circle' => 'Approved',
                        'heroicon-o-x-circle' => 'Rejected'
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('Y-m-d h:i A')
                    ->label('Created Date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('Y-m-d h:i A')
                    ->label('Updated Date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Created' => 'Created',
                        'Withheld' => 'Withheld',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected'
                    ])
                    // ->multiple()
                    ->indicator('Status'),
                SelectFilter::make('district')
                    ->options(Application::DISTRICT)
                    ->label('District')
                    // ->multiple()
                    ->indicator('District'),
                SelectFilter::make('zone_id')
                    ->options(function () {
                        return Zone::pluck('name', 'id')->toArray();
                    })
                    ->multiple()
                    ->label('Zone')
                    ->indicator('Zone')
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['values'],
                            fn(Builder $query, $zoneIds): Builder => $query->whereIn('zone_id', $zoneIds),
                        );
                    }),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ])
            ->filtersFormColumns(3)
            ->actions([
                Action::make('Send Mail')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->form([
                        TextInput::make('Subject')->label('Subject')->required(),
                        Textarea::make('message')->label('Message')->required()
                    ])
                    ->action(function (Application $application, array $data): void {
                        $dispatchData = [
                            'page' => 'emails.send-mail',
                            'application' => $application,
                            'subject' => $data['Subject'],
                            'message' => $data['message'],
                            'mailer' => 'smtp'
                        ];
                        SendEmailJob::dispatch($dispatchData);
                        Notification::make()->title('Mail Sent Successfully')->success()
                            ->send();
                    }),
                Action::make('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(function (Application $record) {
                        $phoneNumber = preg_replace('/^0+/', '', preg_replace('/\D/', '', $record->whatsapp));
                        $date = $record?->zone?->assignment?->date;
                        $time = $record?->zone?->assignment?->time;
                        // Format the date and time using Carbon
                        $formattedDate = Carbon::parse($date)->translatedFormat('j F Y, l');
                        $formattedTime = Carbon::parse($time)->format('g:i A');
                        if ($record->status === 'Approved') {
                            $message = <<<EOT
                Dear Hafiz {$record->full_name},
                
                ‏اَلسَلامُ عَلَيْكُم وَرَحْمَةُ اَللهِ وَبَرَكاتُهُ‎,
                
                We are pleased to inform you that your application for the AP Aslam Holy Qur'an Award 2024 has been successfully reviewed and approved. Congratulations! 🎉
                
                As you know, the competition carries an impressive prize pool of over 20 lakhs rupees, with significant rewards for the winners. We encourage you to continue preparing with dedication.
                
                The competition will cover Thajweed, Qirath, Hifz, and comprehension of the first five and last five Juz of the Qur'an. Preparing across all these areas will help you perform your best.
                
                The area-wise competitions are scheduled to begin in the first week of November, and successful participants will move on to the final competition on December 24, 2024, in Malappuram.
                
                Further details about your competition center and exact dates will be communicated via email. Please keep an eye on your inbox for updates.
                
                We wish you the very best in your preparation. May Allah guide you in your efforts.
                
                If you have any questions, feel free to reach out.
                
                Best regards,
                Coordinator
                AP Aslam Holy Qur'an Award Committee
                📞 9846 310 383
                ✉️ info@aslamquranaward.com
                EOT;
                            // Convert message to UTF-8
                            $message = mb_convert_encoding($message, 'UTF-8', 'UTF-8');
                            // Encode message for URL, preserving emojis
                            $encodedMessage = rawurlencode($message);
                            return "https://wa.me/{$phoneNumber}?text={$encodedMessage}";
                        } else {
                            // If status is not 'Approved', return a WhatsApp link without a message
                            return "https://wa.me/{$phoneNumber}";
                        }
                    }, true),
                Tables\Actions\ViewAction::make()->icon('heroicon-o-eye'),

                // ExportPdfAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('export')->label('Export to Excel')->icon('heroicon-o-document-arrow-down')->action(function (Collection $records) {
                        return Excel::download(new ApplicationExport($records), 'Applications.xlsx');
                    })->deselectRecordsAfterCompletion(),
                    BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->action(fn(Collection $records) => $records->each->update(['status' => 'Approved']))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    // Tables\Actions\DeleteBulkAction::make(),
                ])
            ])
            ->striped()
            // ->columnSpanFull()
            ->paginated([10, 25, 50, 100]);
        // ->responsive();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'view' => Pages\ViewApplication::route('/{record}/view'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
