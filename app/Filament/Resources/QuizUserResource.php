<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizUserResource\Pages;
use App\Filament\Resources\QuizUserResource\RelationManagers\AttemptsRelationManager;
use App\Filament\Resources\QuizUserResource\RelationManagers\WeeklyScoresRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('whatsapp_number')
                                ->label('Mobile Number')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(25)
                                ->tel()
                                ->placeholder('e.g. 9876543210'),

                            Forms\Components\TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->maxLength(150),
                        ]),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\DatePicker::make('dob')
                                ->label('Date of Birth')
                                ->native(false),

                            Forms\Components\TextInput::make('locality')
                                ->label('Place / Locality')
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

                Tables\Columns\TextColumn::make('dob')
                    ->label('Date of Birth')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('weekly_scores_sum_score')
                    ->label('Total Points')
                    ->state(fn (User $record) => ($record->weekly_scores_sum_score ?? 0) . ' pts')
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
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

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

                Tables\Filters\SelectFilter::make('locality')
                    ->label('Place')
                    ->options(fn () => User::whereNotNull('locality')->distinct()->pluck('locality', 'locality')->toArray()),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('toggleStatus')
                        ->label(fn (User $record) => $record->status === 'active' ? 'Disable Account' : 'Activate Account')
                        ->icon(fn (User $record) => $record->status === 'active' ? 'heroicon-o-no-symbol' : 'heroicon-o-check-circle')
                        ->color(fn (User $record) => $record->status === 'active' ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn (User $record) => $record->status === 'active' ? 'Disable Participant Account' : 'Activate Participant Account')
                        ->modalDescription(fn (User $record) => $record->status === 'active'
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
