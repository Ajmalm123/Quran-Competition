<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizUserResource\Pages;
use App\Filament\Resources\QuizUserResource\RelationManagers\AttemptsRelationManager;
use App\Filament\Resources\QuizUserResource\RelationManagers\WeeklyScoresRelationManager;
use App\Mail\QuizParticipantPasswordMail;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class QuizUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Weekly Online Quiz';

    protected static ?string $navigationLabel = 'Participants';

    protected static ?string $pluralLabel = 'Quiz Participants';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Participant Information')
                    ->description('User details registered for the weekly quiz platform.')
                    ->schema([
                        Forms\Components\Grid::make(4)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\Select::make('country_code')
                                ->label('Country')
                                ->options(\App\Services\PhoneService::getSelectOptions())
                                ->searchable()
                                ->default('+91')
                                ->required(),

                            Forms\Components\TextInput::make('whatsapp_number')
                                ->label('Mobile Number')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(25)
                                ->tel()
                                ->placeholder('e.g. +971501234567'),

                            Forms\Components\TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->maxLength(150),
                        ]),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('age')
                                ->label('Age')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(120),

                            Forms\Components\TextInput::make('locality')
                                ->label('Place')
                                ->maxLength(255),

                            Forms\Components\Select::make('status')
                                ->label('Account Status')
                                ->options([
                                    'active' => 'Active',
                                    'disabled' => 'Disabled',
                                ])
                                ->default('active')
                                ->required()
                                ->native(false),
                        ]),
                    ]),

                Forms\Components\Section::make('Security & Password')
                    ->description('Manage login password for this quiz participant. Leave blank to keep existing password.')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('password')
                                ->label('New Password')
                                ->password()
                                ->revealable()
                                ->dehydrateStateUsing(fn(?string $state): ?string => filled($state) ? Hash::make($state) : null)
                                ->dehydrated(fn(?string $state): bool => filled($state))
                                ->required(fn(string $context): bool => $context === 'create')
                                ->minLength(6)
                                ->helperText('Leave empty to keep the existing password (minimum 6 characters).')
                                ->rules(['nullable', 'min:6']),

                            Forms\Components\TextInput::make('password_confirmation')
                                ->label('Confirm New Password')
                                ->password()
                                ->revealable()
                                ->dehydrated(false)
                                ->same('password')
                                ->visible(fn(Forms\Get $get): bool => filled($get('password'))),
                        ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->withCount('quizAttempts')
                    ->withSum('weeklyScores', 'score');
            })
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Participant Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('country_code')
                    ->label('Country')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(function ($state) {
                        $meta = \App\Services\PhoneService::getCountryByCode($state ?? '+91');
                        return $meta ? "{$meta['flag']} {$meta['code']}" : ($state ?? '+91');
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('whatsapp_number')
                    ->label('Mobile Number')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Mobile number copied')
                    ->icon('heroicon-m-phone')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('locality')
                    ->label('Place')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('age')
                    ->label('Age')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('dob')
                    ->label('Date of Birth')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('weekly_scores_sum_score')
                    ->label('Total Points')
                    ->state(fn(User $record) => ($record->weekly_scores_sum_score ?? 0) . ' pts')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('quiz_attempts_count')
                    ->label('Quizzes')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'danger' => 'disabled',
                    ])
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'disabled' => 'Disabled',
                    ]),

                Tables\Filters\SelectFilter::make('country_code')
                    ->label('Country')
                    ->options(\App\Services\PhoneService::getSelectOptions()),

                Tables\Filters\SelectFilter::make('locality')
                    ->label('Place')
                    ->options(fn() => User::whereNotNull('locality')->distinct()->pluck('locality', 'locality')->toArray()),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('changePassword')
                        ->label('Change Password')
                        ->icon('heroicon-o-key')
                        ->color('warning')
                        ->modalHeading(fn(User $record) => "Change Password: {$record->name}")
                        ->modalDescription('Enter a new password or leave blank to auto-generate. It can be emailed to the participant.')
                        ->modalIcon('heroicon-o-key')
                        ->modalWidth('md')
                        ->form([
                            Forms\Components\TextInput::make('email')
                                ->label('Participant Email')
                                ->email()
                                ->default(fn(User $record) => $record->email)
                                ->helperText('Password notification will be sent to this email address.'),

                            Forms\Components\TextInput::make('new_password')
                                ->label('New Password')
                                ->password()
                                ->revealable()
                                ->minLength(6)
                                ->placeholder('Leave blank to auto-generate')
                                ->helperText('Min 6 characters. If left blank, a secure password will be generated automatically.'),

                            Forms\Components\TextInput::make('new_password_confirmation')
                                ->label('Confirm New Password')
                                ->password()
                                ->revealable()
                                ->same('new_password')
                                ->visible(fn(Forms\Get $get): bool => filled($get('new_password'))),

                            Forms\Components\Toggle::make('send_email')
                                ->label('Email new password to participant')
                                ->default(true)
                                ->helperText('Sends an email formatted in the website theme with the new login credentials.'),
                        ])
                        ->action(function (User $record, array $data) {
                            $plainPassword = filled($data['new_password'] ?? null)
                                ? trim($data['new_password'])
                                : (Str::lower(Str::random(4)) . rand(1000, 9999));

                            if (!empty($data['email']) && $data['email'] !== $record->email) {
                                $record->email = trim($data['email']);
                            }

                            $record->password = Hash::make($plainPassword);
                            $record->save();

                            $emailSent = false;
                            if (!empty($data['send_email']) && !empty($record->email)) {
                                try {
                                    Mail::to($record->email)->send(new QuizParticipantPasswordMail($record, $plainPassword, url('/thibyan'), 'admin_update'));
                                    $emailSent = true;
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Password updated but email failed')
                                        ->body("Password updated to '{$plainPassword}'. Email could not be sent: " . $e->getMessage())
                                        ->warning()
                                        ->send();
                                    return;
                                }
                            }

                            Notification::make()
                                ->title('Password updated successfully')
                                ->body($emailSent
                                    ? "Password for {$record->name} changed to '{$plainPassword}' and emailed to {$record->email}."
                                    : "Password for {$record->name} changed to '{$plainPassword}'.")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('emailPassword')
                        ->label('Email New Password')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading(fn(User $record) => "Generate & Email Password: {$record->name}")
                        ->modalDescription(fn(User $record) => !empty($record->email)
                            ? "A new secure password will be generated and emailed directly to {$record->email}."
                            : "This participant does not have an email registered. Please use 'Change Password' to provide an email first.")
                        ->modalSubmitActionLabel('Generate & Send Email')
                        ->visible(fn(User $record) => !empty($record->email))
                        ->action(function (User $record) {
                            $plainPassword = Str::lower(Str::random(4)) . rand(1000, 9999);
                            $record->update([
                                'password' => Hash::make($plainPassword),
                            ]);

                            try {
                                Mail::to($record->email)->send(new QuizParticipantPasswordMail($record, $plainPassword, url('/thibyan'), 'admin_update'));
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Failed to send email')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                                return;
                            }

                            Notification::make()
                                ->title('Password emailed successfully')
                                ->body("A new password ('{$plainPassword}') has been generated and emailed to {$record->email}.")
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('toggleStatus')
                        ->label(fn(User $record) => $record->status === 'active' ? 'Disable Account' : 'Activate Account')
                        ->icon(fn(User $record) => $record->status === 'active' ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
                        ->color(fn(User $record) => $record->status === 'active' ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn(User $record) => $record->status === 'active' ? 'Disable Participant Account' : 'Activate Participant Account')
                        ->modalDescription(fn(User $record) => $record->status === 'active'
                            ? 'The participant will be blocked from logging in or starting new quizzes.'
                            : 'The participant will regain access to the platform.')
                        ->action(function (User $record) {
                            $newStatus = $record->status === 'active' ? 'disabled' : 'active';
                            $record->update(['status' => $newStatus]);

                            Notification::make()
                                ->title("Account {$newStatus}")
                                ->color($newStatus === 'active' ? 'success' : 'danger')
                                ->send();
                        }),

                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AttemptsRelationManager::class,
            WeeklyScoresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizUsers::route('/'),
            'view' => Pages\ViewQuizUser::route('/{record}'),
            'edit' => Pages\EditQuizUser::route('/{record}/edit'),
        ];
    }
}
