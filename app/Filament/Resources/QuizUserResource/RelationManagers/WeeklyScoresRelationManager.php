<?php

namespace App\Filament\Resources\QuizUserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class WeeklyScoresRelationManager extends RelationManager
{
    protected static string $relationship = 'weeklyScores';

    protected static ?string $title = 'Weekly Score Log';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quiz_week_id')
                    ->relationship('quizWeek', 'week_number')
                    ->required(),

                Forms\Components\TextInput::make('score')
                    ->numeric()
                    ->maxValue(30)
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

                Tables\Columns\TextColumn::make('score')
                    ->label('Score Awarded')
                    ->badge()
                    ->formatStateUsing(fn ($state) => "{$state} Pts")
                    ->colors([
                        'success' => 30,
                        'warning' => 20,
                        'info' => 10,
                        'gray' => 0,
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Recorded Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
