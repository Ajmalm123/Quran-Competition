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
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;


class RankingResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Participants Ranking';
    protected static ?string $navigationGroup = 'Applications';
    // protected static ?string $pluralLabel = 'Rankings';
    protected static ?int $navigationSort = 7;

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
                        // Get the current page and per page values
                        $perPage = request()->get('tableRecordsPerPage', 10);
                        $currentPage = request()->get('page', 1);
                        
                        // Calculate the correct rank
                        $rank = (($currentPage - 1) * $perPage) + $rowLoop->iteration;
                        return $rank;
                    })
                    ->badge()
                    ->color('success')
                    ->alignCenter(),
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
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category_id')
                    ->options(function () {
                        return \App\Models\Category::where('is_active', true)->pluck('name', 'id')->toArray();
                    })
                    ->multiple()
                    ->label('Category')
                    ->indicator('Category')
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['values'],
                            fn(Builder $query, $categoryIds): Builder => $query->whereIn('category_id', $categoryIds),
                        );
                    }),
                Filter::make('year')
                    ->form([
                        Select::make('year')
                            ->options(function () {
                                $years = [];
                                $currentYear = now()->year;
                                // Allow more years for historical data (current year back to 2000)
                                for ($i = $currentYear; $i >= 2000; $i--) {
                                    $years[$i] = $i;
                                }
                                return $years;
                            })
                            ->placeholder('Current Year (Default)')
                            ->label('Filter by Year')
                            ->default(now()->year),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['year'],
                            function (Builder $query, $year): Builder {
                                $start = Carbon::create($year, 1, 1)->startOfDay();
                                $end = Carbon::create($year, 12, 31)->endOfDay();
                                return $query->whereBetween('created_at', [$start, $end]);
                            },
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['year'] ?? null) {
                            $indicators['year'] = 'Year: ' . $data['year'] . ' (Jan 1 - Dec 31)';
                        }
                        return $indicators;
                    }),
            ])
            ->striped()
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->whereNotNull('marks')
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRankings::route('/'),
        ];
    }
}