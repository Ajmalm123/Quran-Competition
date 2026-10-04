<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResultResource\Pages;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuizResultResource extends Resource
{
    protected static ?string $model = QuizAttempt::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Weekly Online Quiz';

    protected static ?string $navigationLabel = 'Quiz Results';

    protected static ?string $pluralLabel = 'Quiz Results';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Attempt Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->disabled(),

                        Forms\Components\Select::make('quiz_week_id')
                            ->relationship('quizWeek', 'week_number')
                            ->disabled(),

                        Forms\Components\TextInput::make('weekly_score')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                QuizAttempt::STATUS_IN_PROGRESS => 'In Progress',
                                QuizAttempt::STATUS_COMPLETED => 'Completed',
                                QuizAttempt::STATUS_ABANDONED => 'Abandoned',
                            ])
                            ->disabled(),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Participant & Quiz Details')
                    ->columns(4)
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Participant')
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('user.whatsapp_number')
                            ->label('WhatsApp Number')
                            ->badge()
                            ->color('info'),

                        Infolists\Components\TextEntry::make('quizWeek.week_number')
                            ->label('Quiz Week')
                            ->formatStateUsing(fn ($state) => "Week {$state}")
                            ->badge(),

                        Infolists\Components\TextEntry::make('weekly_score')
                            ->label('Final Score')
                            ->badge()
                            ->formatStateUsing(fn ($state) => "{$state} / 30 Points")
                            ->color(fn ($state) => match ($state) {
                                30 => 'success',
                                20 => 'warning',
                                10 => 'info',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn ($state) => $state === QuizAttempt::STATUS_COMPLETED ? 'success' : 'warning'),

                        Infolists\Components\TextEntry::make('started_at')
                            ->label('Started At')
                            ->dateTime('d M Y, h:i:s A'),

                        Infolists\Components\TextEntry::make('submitted_at')
                            ->label('Submitted At')
                            ->dateTime('d M Y, h:i:s A'),

                        Infolists\Components\TextEntry::make('duration')
                            ->label('Duration')
                            ->state(function (QuizAttempt $record): string {
                                if (!$record->started_at || !$record->submitted_at) {
                                    return 'Incomplete';
                                }
                                $seconds = $record->started_at->diffInSeconds($record->submitted_at);
                                $minutes = floor($seconds / 60);
                                $remSeconds = $seconds % 60;
                                return "{$minutes}m {$remSeconds}s";
                            }),
                    ]),

                Infolists\Components\Section::make('Attempted Questions & Submitted Answers')
                    ->description('Review the 3 randomly selected questions, selected options, and correctness.')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('attemptQuestions')
                            ->label('')
                            ->schema([
                                Infolists\Components\Grid::make(12)->schema([
                                    Infolists\Components\TextEntry::make('display_order')
                                        ->label('Q#')
                                        ->badge()
                                        ->color('primary')
                                        ->columnSpan(1),

                                    Infolists\Components\TextEntry::make('question.question_text')
                                        ->label('Question Text')
                                        ->columnSpan(7)
                                        ->weight('medium'),

                                    Infolists\Components\TextEntry::make('user_answer')
                                        ->label('Selected')
                                        ->state(function ($record) {
                                            $ans = Answer::where('attempt_id', $record->attempt_id)
                                                ->where('question_id', $record->question_id)
                                                ->first();
                                            return $ans?->selected_option ? "Option {$ans->selected_option}" : 'Not Answered';
                                        })
                                        ->badge()
                                        ->color(function ($record) {
                                            $ans = Answer::where('attempt_id', $record->attempt_id)
                                                ->where('question_id', $record->question_id)
                                                ->first();
                                            return $ans?->is_correct ? 'success' : 'danger';
                                        })
                                        ->columnSpan(2),

                                    Infolists\Components\TextEntry::make('question.correct_option')
                                        ->label('Correct')
                                        ->formatStateUsing(fn ($state) => "Option {$state}")
                                        ->badge()
                                        ->color('success')
                                        ->columnSpan(2),
                                ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['user', 'quizWeek']))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Participant')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.whatsapp_number')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('user.locality')
                    ->label('Locality')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('quizWeek.week_number')
                    ->label('Week')
                    ->formatStateUsing(fn ($state) => "Week {$state}")
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('weekly_score')
                    ->label('Score')
                    ->badge()
                    ->formatStateUsing(fn ($state) => "{$state} / 30")
                    ->colors([
                        'success' => 30,
                        'warning' => 20,
                        'info' => 10,
                        'gray' => 0,
                    ])
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_perfect')
                    ->label('Perfect 30/30')
                    ->boolean()
                    ->state(fn (QuizAttempt $record) => $record->weekly_score === 30)
                    ->trueIcon('heroicon-s-star')
                    ->trueColor('warning')
                    ->falseIcon('heroicon-o-minus')
                    ->falseColor('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => QuizAttempt::STATUS_COMPLETED,
                        'warning' => QuizAttempt::STATUS_IN_PROGRESS,
                        'danger' => QuizAttempt::STATUS_ABANDONED,
                    ]),

                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('quiz_week_id')
                    ->label('Quiz Week')
                    ->relationship('quizWeek', 'week_number')
                    ->getOptionLabelFromRecordUsing(fn (QuizWeek $record) => "Week {$record->week_number} ({$record->quiz_date->format('d M Y')})"),

                Tables\Filters\TernaryFilter::make('perfect_score')
                    ->label('Perfect Score (30/30)')
                    ->queries(
                        true: fn (Builder $query) => $query->where('weekly_score', 30),
                        false: fn (Builder $query) => $query->where('weekly_score', '<', 30),
                    ),

                Tables\Filters\SelectFilter::make('weekly_score')
                    ->label('Exact Score')
                    ->options([
                        '30' => '30 Points (Perfect)',
                        '20' => '20 Points',
                        '10' => '10 Points',
                        '0' => '0 Points',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        QuizAttempt::STATUS_COMPLETED => 'Completed',
                        QuizAttempt::STATUS_IN_PROGRESS => 'In Progress',
                        QuizAttempt::STATUS_ABANDONED => 'Abandoned',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizResults::route('/'),
            'view' => Pages\ViewQuizResult::route('/{record}'),
        ];
    }
}
