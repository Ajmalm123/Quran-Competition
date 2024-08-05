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
                    ->getStateUsing(fn ($record) => Carbon::parse($record->date_of_birth)->age)
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
                        return Zone::pluck('name', 'id');
                    })
                    ->label('Zone')
                    ->indicator('Zone'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
                            *പ്രിയപ്പെട്ട {$record->full_name}*,
                            
                            السلام عليكم ورحمة الله
                            
                            *'എ.പി. അസ്‌ലം ഹോളി ഖുർആൻ അവാർഡ് 2024'  ലേക്കുള്ള നിങ്ങളുടെ അപേക്ഷ അംഗീകരിച്ചിരിക്കുന്നു എന്ന് സന്തോഷപൂർവം അറിയിക്കുന്നു.*🎊
                            
                            താങ്കൾ പങ്കെടുക്കേണ്ട പ്രാഥമിക മത്സരത്തിൻ്റെ വിവരങ്ങൾ താഴെ കൊടുക്കുന്നു.
                            
                            🗓️Date: *{$formattedDate}*
                            
                            🕐Reporting Time: *{$formattedTime}*
                            
                            📍Location: *{$record?->zone?->assignment?->center_id}*
                            
                            മത്സരത്തിന് മുന്നോടിയായി താഴെ പറയുന്ന കാര്യങ്ങൾ ശ്രദ്ധിക്കണമെന്ന് വിനീതമായി അഭ്യർത്ഥിക്കുന്നു. 
                            
                            1. നിർദ്ദേശിച്ച റിപ്പോർട്ടിങ് സമയത്ത് തന്നെ സ്ഥലത്തെത്തി രജിസ്റ്റർ ചെയ്യേണ്ടതാണ്. മത്സരം തുടങ്ങിയ ശേഷം എത്തുന്നവരെ മത്സരിക്കാൻ അനുവദിക്കുന്നതല്ല. 
                            
                            2. ഹിഫ്ള്,പാരായണ നിയമങ്ങൾ, മഖാരിജുൽ ഹുറൂഫ്, ആശയം (ആദ്യത്തെ അഞ്ച് ജുസ്ഉകളിലെയും അവസാനത്തെ അഞ്ചു ജുസ്ഉകളിലെയും)എന്നിവയുടെ അടിസ്ഥാനത്തിലായിരിക്കും മത്സരം നടക്കുക.ആ രംഗത്ത് സംഭവിക്കുന്ന ഓരോ പിഴവുകളും മത്സരാർത്ഥികളുടെ മാർക്ക് കുറയ്ക്കുന്നതാണ്.
                            
                            4. മത്സരാർത്ഥികളോടുള്ള ചോദ്യങ്ങൾ മലയാളത്തിലായിരിക്കും; ആയത്തുകളുടെ ആശയങ്ങളുമായി ബന്ധപ്പെട്ട ചോദ്യങ്ങൾക്ക് മറുപടി പറയേണ്ടതും മലയാളത്തിലാണ്.
                            
                            5. അപേക്ഷയോടൊപ്പം സമർപ്പിച്ച രേഖകളുടെ ഒറിജിനൽ കയ്യിൽ കരുതേണ്ടതാണ്.
                            
                            അല്ലാഹു അനുഗ്രഹിക്കട്ടെ, ആമീൻ. 
                            
                            മറ്റു വിവരങ്ങൾ തുടർന്ന് അറിയിക്കുന്നതാണ്.
                            
                            💬പ്രോഗ്രാമുമായി ബന്ധപ്പെട്ട വിവരങ്ങൾക്കും സംശയങ്ങൾക്കും ബന്ധപ്പെടുക.
                            
                            info@aslamquranaward.com
                            
                            കൺവീനർ
                            എ.പി. അസ്‌ലം ഹോളി ഖുർആൻ അവാർഡ് 2024
                            EOT;

                            // Convert message to UTF-8
                            $message = mb_convert_encoding($message, 'UTF-8', 'UTF-8');
                            // Encode message for URL
                            $encodedMessage = urlencode($message);
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
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'Approved']))
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
