<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizLeaderboardResource\Pages;
use App\Models\QuizAttempt;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuizLeaderboardResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Weekly Online Quiz';

    protected static ?string $navigationLabel = 'Leaderboard';

    protected static ?string $pluralLabel = 'Cumulative Leaderboard';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->withSum('weeklyScores', 'score')
                    ->withCount('quizAttempts')
                    ->whereHas('weeklyScores')
                    ->orderByDesc('weekly_scores_sum_score');
            })
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('Rank')
                    ->state(function ($record, $rowLoop): string {
                        $perPage = (int) request()->get('tableRecordsPerPage', 25);
                        $page = (int) request()->get('page', 1);
                        $rank = (($page - 1) * $perPage) + $rowLoop->iteration;

                        return match ($rank) {
                            1 => '#1 🥇',
                            2 => '#2 🥈',
                            3 => '#3 🥉',
                            default => "#{$rank}",
                        };
                    })
                    ->badge()
                    ->color(function ($record, $rowLoop): string {
                        $perPage = (int) request()->get('tableRecordsPerPage', 25);
                        $page = (int) request()->get('page', 1);
                        $rank = (($page - 1) * $perPage) + $rowLoop->iteration;

                        return match ($rank) {
                            1 => 'warning',
                            2 => 'gray',
                            3 => 'danger',
                            default => 'primary',
                        };
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Participant Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('locality')
                    ->label('Locality')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->badge()
                    ->color('info')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('weekly_scores_sum_score')
                    ->label('Cumulative Points')
                    ->badge()
                    ->formatStateUsing(fn ($state) => "{$state} Pts")
                    ->color('success')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('quiz_attempts_count')
                    ->label('Quizzes Attempted')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('perfect_scores')
                    ->label('Perfect 30s')
                    ->state(function (User $record): string {
                        $count = QuizAttempt::where('user_id', $record->id)
                            ->where('weekly_score', 30)
                            ->where('status', QuizAttempt::STATUS_COMPLETED)
                            ->count();
                        return "{$count}";
                    })
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray')
                    ->alignCenter(),
            ])
            ->defaultSort('weekly_scores_sum_score', 'desc')
            ->defaultPaginationPageOption(25)
            ->filters([
                Tables\Filters\SelectFilter::make('locality')
                    ->options(fn () => User::whereNotNull('locality')->distinct()->pluck('locality', 'locality')->toArray()),

                Tables\Filters\Filter::make('top_points')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('min_points')
                            ->label('Minimum Points')
                            ->numeric()
                            ->placeholder('e.g. 30'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['min_points'],
                            fn (Builder $query, $points) => $query->having('weekly_scores_sum_score', '>=', $points)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('viewParticipant')
                    ->label('View Profile')
                    ->icon('heroicon-o-eye')
                    ->url(fn (User $record) => QuizUserResource::getUrl('view', ['record' => $record])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizLeaderboards::route('/'),
        ];
    }
}
