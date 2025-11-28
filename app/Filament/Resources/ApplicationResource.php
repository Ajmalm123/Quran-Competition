<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Zone;
use App\Models\Category;
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
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Mail;
use App\Mail\BulkMail;
use Filament\Support\Enums\ActionSize;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;
    protected static ?string $navigationGroup = 'Applications';
    protected static ?int $navigationSort = 2;



    protected static ?string $navigationIcon = 'heroicon-o-document-text';


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
                        Grid::make(1)->schema([
                            Select::make('category_id')
                                ->label('Category')
                                ->options(Category::where('is_active', true)->pluck('name', 'id'))
                                ->required()
                                ->searchable(),
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
                TextColumn::make('time_slot')
                    ->label('Time Slot')
                    ->searchable()
                    ->wrap()
                    ->placeholder('Not assigned'),
                TextColumn::make('category.name')
                    ->label('Category')
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
                SelectFilter::make('category_id')
                    ->options(function () {
                        return Category::where('is_active', true)->pluck('name', 'id')->toArray();
                    })
                    ->multiple()
                    ->label('Category')
                    ->indicator('Category')
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['values'],
                            fn(Builder $query, $categoryIds): Builder => $query->whereIn('category_id', $categoryIds),
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
                Filter::make('year')
                    ->form([
                        Select::make('year')
                            ->options(function () {
                                $years = [];
                                $currentYear = now()->year;
                                // Allow more years for historical data (current year back to 2000)
                                for ($i = $currentYear; $i >= 2024; $i--) {
                                    $years[$i] = $i;
                                }
                                return $years;
                            })
                            ->placeholder('Current Year (Default)')
                            ->label('Filter by Year')
                            ->default(now()->year),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['year'],
                            function (Builder $query, $year): Builder {
                                $start = Carbon::create($year, 1, 1)->startOfDay();
                                $end = Carbon::create($year, 12, 31)->endOfDay();
                                return $query->whereBetween('created_at', [$start, $end]);
                            },
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['year'] ?? null) {
                            $indicators['year'] = 'Year: ' . $data['year'] . ' (Jan 1 - Dec 31)';
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
                        if ($record->status === 'Approved') {
                            $genderTitle = $record->gender === 'Male' ? 'ഹാഫിസ്' : 'ഹാഫിസ';

                            // Shorter message for WhatsApp URL
                            $shortMessage = "السَّلامُ عَلَيْكُم ورَحْمَةُ اللهِ وَبَرَكاتُهُ\n\nപ്രിയപ്പെട്ട {$genderTitle} {$record->full_name},\n\nഎ പി അസ്‌ലം ഹോളി ഖുർആൻ അവാർഡ് 2025 മത്സരത്തിൽ പങ്കെടുക്കുന്നതിനായി താങ്കൾ സമർപ്പിച്ച അപേക്ഷ പരിശോധിക്കുകയും അംഗീകരിക്കുകയും ചെയ്തതായി അറിയിക്കുന്നതിൽ ഞങ്ങൾക്ക് സന്തോഷമുണ്ട്. അഭിനന്ദനങ്ങൾ!\n\nതാങ്കൾ മനസ്സിലാക്കിയത് പോലെ വിശുദ്ധ ഖുർആൻ പരിപൂർണ്ണമായ മനഃപാഠവും തജ് വീദ് നിയമങ്ങൾ അനുസരിച്ചുള്ള പാരായണവുമായിരിക്കും മത്സരത്തിന്റെ ഭാഗമായി പരിശോധിക്കുക. അതോടൊപ്പം 22 വയസ്സിന് താഴെയുള്ള ആൺകുട്ടികളുടെ വിഭാഗത്തിൽ ആദ്യ അവസാന അഞ്ചു ജുസ്ഉകൾ ഒഴികെയുള്ള 20 ജുസ്ഇന്റെയും, 22 വയസ്സിന് താഴെയുള്ള പെൺകുട്ടികളുടെ വിഭാഗം മത്സരങ്ങളിൽ ഖുർആനിലെ ആദ്യ അവസാന അഞ്ചു വീതം ജുസ്ഉകളുടെയും ആശയസംബന്ധമായ പരിശോധനയും ഉണ്ടായിരിക്കും. 13 വയസ്സിന് താഴെയുള്ള കുട്ടികളുടെ വിഭാഗത്തിൽ ഖുർആൻ പൂർണ്ണമായും മനഃപാഠമാക്കിയിട്ടുണ്ടോ എന്ന് മാത്രമായിരിക്കും പരിശോധിക്കുക.\n\nനവംബർ അവസാനവാരത്തിൽ നടക്കുന്ന സ്ക്രീനിങ് മത്സരങ്ങളുടെ സ്ഥലവും തീയതിയും സമയവും ഇമെയിൽ സന്ദേശമായി താങ്കൾക്ക് പിന്നീട് ലഭിക്കുന്നതായിരിക്കും.\n\nമത്സരത്തിനു വേണ്ടി നന്നായി തയ്യാറെടുക്കുകയും പരിശ്രമിക്കുകയും ചെയ്യണമെന്ന് അറിയിക്കുന്നു.\n\nകൂടുതൽ വിവരങ്ങൾക്ക് ഞങ്ങളെ ബന്ധപ്പെടാവുന്നതാണ്.\n\nവിശ്വസ്തതയോടെ,\nകോ ഓർഡിനേറ്റർ\nഎ പി അസ്‌ലം ഹോളി ഖുർആൻ അവാർഡ് കമ്മിറ്റി\n\n9846310383\ninfo@aslamquranaward.com";

                            // Encode message for URL - use rawurlencode for better compatibility
                            $encodedMessage = rawurlencode($shortMessage);
                            return "https://api.whatsapp.com/send/?phone={$phoneNumber}&text={$encodedMessage}";
                        } else {
                            // If status is not 'Approved', return a WhatsApp link without a message
                            return "https://api.whatsapp.com/send/?phone={$phoneNumber}";
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
                    BulkAction::make('sendMail')
                        ->label('Send Center Details Mail')
                        ->icon('heroicon-o-envelope')
                        ->color('success')
                        ->action(function (Collection $records) {
                            foreach ($records as $application) {
                                $mailData = [
                                    'applicant_name' => $application->full_name,
                                    'zone' => $application->zone->name ?? 'N/A',
                                    'center_name' => $application->zone->assignment->center_id ?? 'N/A',
                                    // 'center_code' => $application->zone->assignment->center_code ?? 'N/A',
                                    'location' => $application->zone->assignment->location ?? 'N/A',
                                    'date' => $application->zone->assignment->date ?? 'N/A',
                                    'reporting_time' => $application->zone->assignment->time ?? 'N/A',
                                    'subject' => 'AP Aslam Holy Quran Award ' . date('Y') . ' - Preliminary Round Competition Details',
                                    'page' => 'emails.bulk-mail',
                                ];

                                Mail::mailer('smtp2')->to($application->email)->queue(new BulkMail($mailData));
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
                        ->modalDescription('Are you sure you want to send emails to all selected applications?')
                        ->modalSubmitActionLabel('Yes, Send Emails')
                        ->modalCancelActionLabel('Cancel')
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('sendAdmitCardMail')
                        ->label('Send Admit Card Mail')
                        ->icon('heroicon-o-envelope')
                        ->color('success')
                        ->action(function (Collection $records) {
                            foreach ($records as $application) {
                                $pdfPath = public_path("storage/admit_cards/{$application->application_id}.pdf");
                                $mailData = [
                                    'applicant_name' => $application->full_name,
                                    'zone' => $application->zone->name ?? 'N/A',
                                    'center_name' => $application->zone->assignment->center_id ?? 'N/A',
                                    'location' => $application->zone->assignment->location ?? 'N/A',
                                    'date' => $application->zone->assignment->date ?? 'N/A',
                                    'reporting_time' => $application->zone->assignment->time ?? 'N/A',
                                    'subject' => 'Admit Card',
                                    'page' => 'emails.admit-card-bulk-mail',
                                    'pdfPath' => $pdfPath,
                                ];

                                Mail::mailer('smtp2')->to($application->email)->queue(new BulkMail($mailData));
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
                        ->modalDescription('Are you sure you want to send emails with admit cards to all selected applications?')
                        ->modalSubmitActionLabel('Yes, Send Emails')
                        ->modalCancelActionLabel('Cancel')
                        ->deselectRecordsAfterCompletion(),
                ])
            ])
            ->striped()
            // ->columnSpanFull()
            ->paginated([10, 25, 50, 100, 'all'])
            ->modifyQueryUsing(function (Builder $query): Builder {
                return $query;
            });
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
