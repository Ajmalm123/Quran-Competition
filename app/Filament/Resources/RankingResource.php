<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Application;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RankingResource\Pages;


class RankingResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Participants Ranking';
    // protected static ?string $pluralLabel = 'Rankings';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('Rank')
                    ->state(static function ($record, $rowLoop): string {
                        return $rowLoop->iteration;
                    })
                    ->badge()
                    ->color('success')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('application_id')
                    ->label('Application ID')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Application ID copied')
                    // ->copyMessageDuration(1500)
                    // ->icon('heroicon-m-identification')
                    ->alignCenter(),
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),
                    // ->icon('heroicon-m-user'),
                TextColumn::make('zone.name')
                    ->label('Zone')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('warning'),
                    // ->icon('heroicon-m-map-pin'),
                TextColumn::make('marks')
                    ->label('Total Marks')
                    ->sortable()
                    ->searchable()
                    // ->badge()
                    ->alignCenter()
                    ->size('lg'),
                    // ->icon('heroicon-m-academic-cap'),
            ])
            ->defaultSort('marks', 'desc')
            ->striped()
            ->modifyQueryUsing(fn(Builder $query) => $query->whereNotNull('marks'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRankings::route('/'),
        ];
    }
}