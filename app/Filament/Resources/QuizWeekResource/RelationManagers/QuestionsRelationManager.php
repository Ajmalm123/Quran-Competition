<?php

namespace App\Filament\Resources\QuizWeekResource\RelationManagers;

use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $recordTitleAttribute = 'question_text';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Order #')
                        ->numeric()
                        ->default(fn () => ($this->getOwnerRecord()->questions()->max('sort_order') ?? 0) + 1)
                        ->required()
                        ->minValue(1)
                        ->maxValue(6)
                        ->columnSpan(1),

                    Forms\Components\Select::make('correct_option')
                        ->label('Correct Option')
                        ->options([
                            'A' => 'Option A',
                            'B' => 'Option B',
                            'C' => 'Option C',
                            'D' => 'Option D',
                        ])
                        ->required()
                        ->native(false)
                        ->columnSpan(2),
                ]),

                Forms\Components\Textarea::make('question_text')
                    ->label('Question Text')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Section::make('Answer Options (A, B, C, D)')
                    ->description('Provide the 4 multiple choice options. Exactly one must match the Correct Option above.')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('option_a')
                                ->label('Option A')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('option_b')
                                ->label('Option B')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('option_c')
                                ->label('Option C')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('option_d')
                                ->label('Option D')
                                ->required()
                                ->maxLength(255),
                        ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description(fn () => 'Every weekly quiz must have exactly 6 questions. Currently configured: ' . $this->getOwnerRecord()->questions()->count() . ' / 6')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('question_text')
                    ->label('Question')
                    ->wrap()
                    ->searchable()
                    ->limit(70),

                Tables\Columns\TextColumn::make('correct_option')
                    ->label('Correct')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('correct_option_text')
                    ->label('Correct Answer Text')
                    ->wrap()
                    ->color('gray')
                    ->limit(50),
            ])
            ->defaultSort('sort_order', 'asc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Question')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Add Quiz Question (Total 6 required)')
                    ->before(function (Tables\Actions\CreateAction $action) {
                        if ($this->getOwnerRecord()->questions()->count() >= 6) {
                            Notification::make()
                                ->danger()
                                ->title('Question Limit Reached')
                                ->body('Each weekly quiz must have exactly 6 questions. Please edit or delete an existing question.')
                                ->send();
                            $action->halt();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
