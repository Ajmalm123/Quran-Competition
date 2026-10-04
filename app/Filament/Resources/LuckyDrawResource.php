<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LuckyDrawResource\Pages;
use App\Models\LuckyDraw;
use App\Models\QuizAttempt;
use App\Models\QuizWeek;
use App\Models\User;
use App\Services\QuizService;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LuckyDrawResource extends Resource
{
    protected static ?string $model = LuckyDraw::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Weekly Online Quiz';

    protected static ?string $navigationLabel = 'Lucky Draw';

    protected static ?string $pluralLabel = 'Weekly Lucky Draws';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Weekly Lucky Draw Details')
                    ->description('Lucky draw for participants achieving 30/30 perfect score.')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('quiz_week_id')
                                ->label('Quiz Week')
                                ->relationship('quizWeek', 'week_number')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->disabled(fn (?LuckyDraw $record) => $record !== null),

                            Forms\Components\Select::make('winner_user_id')
                                ->label('Selected Winner')
                                ->relationship('winner', 'name')
                                ->searchable()
                                ->disabled(fn (?LuckyDraw $record) => $record?->isLocked() ?? false),

                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    LuckyDraw::STATUS_PENDING => 'Pending',
                                    LuckyDraw::STATUS_DRAWN => 'Drawn (Awaiting Confirmation)',
                                    LuckyDraw::STATUS_CONFIRMED => 'Confirmed (Locked)',
                                    LuckyDraw::STATUS_PUBLISHED => 'Published (Live on Website)',
                                ])
                                ->default(LuckyDraw::STATUS_PENDING)
                                ->required()
                                ->native(false),
                        ]),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\DateTimePicker::make('drawn_at')
                                ->label('Drawn At')
                                ->disabled(),

                            Forms\Components\DateTimePicker::make('confirmed_at')
                                ->label('Confirmed At')
                                ->disabled(),

                            Forms\Components\DateTimePicker::make('published_at')
                                ->label('Published At')
                                ->disabled(),
                        ]),

                        Forms\Components\Textarea::make('notes')
                            ->label('Audit / Draw Notes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['quizWeek', 'winner']))
            ->columns([
                Tables\Columns\TextColumn::make('quizWeek.week_number')
                    ->label('Quiz Week')
                    ->formatStateUsing(fn ($state) => "Week {$state}")
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quizWeek.quiz_date')
                    ->label('Quiz Date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('eligible_count')
                    ->label('30/30 Participants')
                    ->badge()
                    ->color('info')
                    ->state(function (LuckyDraw $record): string {
                        $count = QuizAttempt::where('quiz_week_id', $record->quiz_week_id)
                            ->where('status', QuizAttempt::STATUS_COMPLETED)
                            ->where('weekly_score', 30)
                            ->count();
                        return "{$count} Eligible";
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('winner.name')
                    ->label('Winner')
                    ->weight('bold')
                    ->icon('heroicon-m-trophy')
                    ->iconColor('warning')
                    ->default('Not Drawn Yet')
                    ->searchable(),

                Tables\Columns\TextColumn::make('winner.whatsapp_number')
                    ->label('WhatsApp')
                    ->badge()
                    ->color('info')
                    ->copyable()
                    ->default('—'),

                Tables\Columns\TextColumn::make('winner.locality')
                    ->label('Locality')
                    ->badge()
                    ->color('gray')
                    ->default('—'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Draw Status')
                    ->badge()
                    ->colors([
                        'gray' => LuckyDraw::STATUS_PENDING,
                        'warning' => LuckyDraw::STATUS_DRAWN,
                        'info' => LuckyDraw::STATUS_CONFIRMED,
                        'success' => LuckyDraw::STATUS_PUBLISHED,
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        LuckyDraw::STATUS_PENDING => 'Pending',
                        LuckyDraw::STATUS_DRAWN => 'Drawn (Unconfirmed)',
                        LuckyDraw::STATUS_CONFIRMED => 'Confirmed (Locked)',
                        LuckyDraw::STATUS_PUBLISHED => 'Published (Live)',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('drawn_at')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('confirmed_at')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        LuckyDraw::STATUS_PENDING => 'Pending',
                        LuckyDraw::STATUS_DRAWN => 'Drawn',
                        LuckyDraw::STATUS_CONFIRMED => 'Confirmed',
                        LuckyDraw::STATUS_PUBLISHED => 'Published',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('runLuckyDraw')
                        ->label('Run Random Lucky Draw')
                        ->icon('heroicon-o-sparkles')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Execute Weekly Lucky Draw')
                        ->modalDescription('The backend will randomly select one winner from all participants who achieved a perfect score of 30/30 in this week.')
                        ->visible(fn (LuckyDraw $record) => $record->status === LuckyDraw::STATUS_PENDING)
                        ->action(function (LuckyDraw $record) {
                            try {
                                $service = app(QuizService::class);
                                $service->executeLuckyDraw($record->quizWeek);

                                Notification::make()
                                    ->success()
                                    ->title('Lucky Draw Executed!')
                                    ->body("Winner selected: {$record->fresh()->winner?->name}")
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->danger()
                                    ->title('Lucky Draw Failed')
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        }),

                    Tables\Actions\Action::make('confirmWinner')
                        ->label('Confirm Winner (Lock)')
                        ->icon('heroicon-o-lock-closed')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Confirm & Lock Winner')
                        ->modalDescription('Once confirmed, the winner is protected against accidental changes.')
                        ->visible(fn (LuckyDraw $record) => $record->status === LuckyDraw::STATUS_DRAWN)
                        ->action(function (LuckyDraw $record) {
                            $service = app(QuizService::class);
                            $service->confirmWinner($record);

                            Notification::make()
                                ->success()
                                ->title('Winner Confirmed & Locked')
                                ->send();
                        }),

                    Tables\Actions\Action::make('publishWinner')
                        ->label('Publish to Public Website')
                        ->icon('heroicon-o-megaphone')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Publish Weekly Winner')
                        ->modalDescription('The winner will become visible on the public React website.')
                        ->visible(fn (LuckyDraw $record) => in_array($record->status, [LuckyDraw::STATUS_DRAWN, LuckyDraw::STATUS_CONFIRMED]))
                        ->action(function (LuckyDraw $record) {
                            $service = app(QuizService::class);
                            $service->publishWinner($record);

                            Notification::make()
                                ->success()
                                ->title('Winner Published Successfully!')
                                ->send();
                        }),

                    Tables\Actions\Action::make('reDraw')
                        ->label('Re-Draw Winner')
                        ->icon('heroicon-o-arrow-path')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Re-run Lucky Draw')
                        ->modalDescription('Are you sure you want to re-run the random selection? This will select a new winner.')
                        ->visible(fn (LuckyDraw $record) => in_array($record->status, [LuckyDraw::STATUS_DRAWN, LuckyDraw::STATUS_CONFIRMED]))
                        ->form([
                            Forms\Components\Textarea::make('audit_reason')
                                ->label('Reason for Re-drawing')
                                ->required()
                                ->placeholder('e.g. Previous winner was disqualified or requested re-draw'),
                        ])
                        ->action(function (LuckyDraw $record, array $data) {
                            try {
                                $service = app(QuizService::class);
                                $service->executeLuckyDraw($record->quizWeek, $data['audit_reason'] ?? null);

                                Notification::make()
                                    ->warning()
                                    ->title('Lucky Draw Re-Executed')
                                    ->body("New Winner: {$record->fresh()->winner?->name}")
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->danger()
                                    ->title('Re-draw Failed')
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        }),

                    Tables\Actions\Action::make('viewEligible')
                        ->label('View 30/30 Participants')
                        ->icon('heroicon-o-user-group')
                        ->color('gray')
                        ->modalHeading(fn (LuckyDraw $record) => "Eligible 30/30 Participants - Week {$record->quizWeek->week_number}")
                        ->modalContent(function (LuckyDraw $record) {
                            $users = app(QuizService::class)->getEligibleLuckyDrawParticipants($record->quizWeek);
                            return view('filament.modals.eligible-participants', ['users' => $users]);
                        }),

                    Tables\Actions\EditAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLuckyDraws::route('/'),
        ];
    }
}
