<?php

namespace App\Filament\Resources\QuizUserResource\RelationManagers;

use App\Models\QuizAttempt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'quizAttempts';

    protected static ?string $title = 'Quiz Participation History';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quiz_week_id')
                    ->relationship('quizWeek', 'week_number')
                    ->required(),

                Forms\Components\TextInput::make('weekly_score')
                    ->numeric()
                    ->maxValue(30)
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        QuizAttempt::STATUS_IN_PROGRESS => 'In Progress',
                        QuizAttempt::STATUS_COMPLETED => 'Completed',
                        QuizAttempt::STATUS_ABANDONED => 'Abandoned',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quizWeek.week_number')
                    ->label('Week')
                    ->formatStateUsing(fn ($state) => "Week {$state}")
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quizWeek.quiz_date')
                    ->label('Quiz Date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('weekly_score')
                    ->label('Weekly Score')
                    ->badge()
                    ->formatStateUsing(fn ($state) => "{$state} / 30")
                    ->colors([
                        'success' => 30,
                        'warning' => 20,
                        'info' => 10,
                        'gray' => 0,
                    ])
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_perfect')
                    ->label('Perfect 30')
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
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
