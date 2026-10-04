<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizWeekResource\Pages;
use App\Filament\Resources\QuizWeekResource\RelationManagers\QuestionsRelationManager;
use App\Models\LuckyDraw;
use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use App\Services\QuizService;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuizWeekResource extends Resource
{
    protected static ?string $model = QuizWeek::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Weekly Online Quiz';

    protected static ?string $navigationLabel = 'Weekly Quizzes';

    protected static ?string $pluralLabel = 'Weekly Quizzes';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Weekly Quiz Configuration')
                    ->description('Schedule the weekly quiz dates, operational timings, and publication status.')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('week_number')
                                ->label('Week Number')
                                ->numeric()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->minValue(1)
                                ->placeholder('e.g. 1'),

                            Forms\Components\DatePicker::make('quiz_date')
                                ->label('Quiz Date')
                                ->required()
                                ->native(false),

                            Forms\Components\Select::make('status')
                                ->label('Quiz Status')
                                ->options([
                                    QuizWeek::STATUS_DRAFT => 'Draft (Editing)',
                                    QuizWeek::STATUS_UPCOMING => 'Upcoming (Scheduled)',
                                    QuizWeek::STATUS_LIVE => 'Live',
                                    QuizWeek::STATUS_COMPLETED => 'Completed',
                                    QuizWeek::STATUS_CANCELLED => 'Cancelled',
                                ])
                                ->default(QuizWeek::STATUS_DRAFT)
                                ->required()
                                ->native(false),
                        ]),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DateTimePicker::make('start_at')
                                ->label('Live Start Time')
                                ->required()
                                ->native(false),

                            Forms\Components\DateTimePicker::make('end_at')
                                ->label('Live End Time')
                                ->required()
                                ->after('start_at')
                                ->native(false),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount(['questions', 'attempts']))
            ->columns([
                Tables\Columns\TextColumn::make('week_number')
                    ->label('Week')
                    ->formatStateUsing(fn ($state) => "Week {$state}")
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quiz_date')
                    ->label('Date & Day')
                    ->date('D, d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_at')
                    ->label('Start Time')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_at')
                    ->label('End Time')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Questions Pool')
                    ->badge()
                    ->state(fn ($record) => "{$record->questions_count} / 6 Questions")
                    ->color(fn ($record) => $record->questions_count === 6 ? 'success' : 'warning')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('computed_status')
                    ->label('Live Status')
                    ->badge()
                    ->colors([
                        'gray' => QuizWeek::STATUS_DRAFT,
                        'info' => QuizWeek::STATUS_UPCOMING,
                        'success' => QuizWeek::STATUS_LIVE,
                        'warning' => QuizWeek::STATUS_COMPLETED,
                        'danger' => QuizWeek::STATUS_CANCELLED,
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('attempts_count')
                    ->label('Participants')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),
            ])
            ->defaultSort('week_number', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        QuizWeek::STATUS_DRAFT => 'Draft',
                        QuizWeek::STATUS_UPCOMING => 'Upcoming',
                        QuizWeek::STATUS_LIVE => 'Live',
                        QuizWeek::STATUS_COMPLETED => 'Completed',
                        QuizWeek::STATUS_CANCELLED => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Tables\Actions\Action::make('publish')
                        ->label('Publish Quiz')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Publish Weekly Quiz')
                        ->modalDescription('The quiz will be marked active. It will transition to Upcoming/Live according to the schedule.')
                        ->visible(fn (QuizWeek $record) => in_array($record->status, [QuizWeek::STATUS_DRAFT, QuizWeek::STATUS_CANCELLED]))
                        ->action(function (QuizWeek $record) {
                            if (!$record->canPublish()) {
                                Notification::make()
                                    ->danger()
                                    ->title('Cannot Publish Quiz')
                                    ->body('A weekly quiz must have exactly 6 complete questions with 4 options and valid correct answer before publishing.')
                                    ->send();
                                return;
                            }

                            $newStatus = Carbon::now()->gte($record->start_at) ? QuizWeek::STATUS_LIVE : QuizWeek::STATUS_UPCOMING;
                            $record->update(['status' => $newStatus]);

                            Notification::make()
                                ->success()
                                ->title('Quiz Published Successfully')
                                ->send();
                        }),

                    Tables\Actions\Action::make('unpublish')
                        ->label('Revert to Draft')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->visible(fn (QuizWeek $record) => $record->status !== QuizWeek::STATUS_DRAFT && $record->status !== QuizWeek::STATUS_COMPLETED)
                        ->action(function (QuizWeek $record) {
                            $record->update(['status' => QuizWeek::STATUS_DRAFT]);
                            Notification::make()
                                ->info()
                                ->title('Quiz Reverted to Draft')
                                ->send();
                        }),

                    Tables\Actions\Action::make('cancel')
                        ->label('Cancel Quiz')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (QuizWeek $record) => $record->status !== QuizWeek::STATUS_CANCELLED && $record->status !== QuizWeek::STATUS_COMPLETED)
                        ->action(function (QuizWeek $record) {
                            $record->update(['status' => QuizWeek::STATUS_CANCELLED]);
                            Notification::make()
                                ->warning()
                                ->title('Quiz Cancelled')
                                ->send();
                        }),

                    Tables\Actions\Action::make('luckyDraw')
                        ->label('Run Lucky Draw')
                        ->icon('heroicon-o-sparkles')
                        ->color('warning')
                        ->visible(fn (QuizWeek $record) => $record->computed_status === QuizWeek::STATUS_COMPLETED)
                        ->url(fn (QuizWeek $record) => route('filament.admin.resources.lucky-draws.index', ['tableFilters[quiz_week_id][value]' => $record->id])),

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
            QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizWeeks::route('/'),
            'create' => Pages\CreateQuizWeek::route('/create'),
            'view' => Pages\ViewQuizWeek::route('/{record}'),
            'edit' => Pages\EditQuizWeek::route('/{record}/edit'),
        ];
    }
}
