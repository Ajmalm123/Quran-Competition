<?php

namespace App\Filament\Resources;

use Closure;
use Carbon\Carbon;
use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ZoneAssignment;
use Tables\Columns\DateColumn;
use Tables\Columns\TimeColumn;
use Tables\Filters\DateFilter;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ZoneAssignmentResource\Pages;
use App\Filament\Resources\ZoneAssignmentResource\RelationManagers;
use App\Filament\Resources\ZoneAssignmentResource\Widgets\ZoneApplicationStats;

class ZoneAssignmentResource extends Resource
{
    protected static ?string $model = ZoneAssignment::class;
    protected static ?string $navigationLabel = 'Zone Assignment';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?int $navigationSort = 3;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Zone Assignment')
                    ->description('Assign a center to a zone with date and time.')
                    ->icon('heroicon-o-map')
                    ->schema([
                        Forms\Components\Select::make('zone_id')
                            ->label('Zone')
                            ->options(Zone::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                if ($state) {
                                    $existingAssignment = ZoneAssignment::where('zone_id', $state)->first();
                                    if ($existingAssignment) {
                                        $set('center_id', $existingAssignment->center_id);
                                    } else {
                                        $set('center_id', null);
                                    }
                                }
                            })
                            ->reactive()
                            ->rules([
                                function (Forms\Get $get) {
                                    return function (string $attribute, $value, Closure $fail) use ($get) {
                                        $existingAssignment = ZoneAssignment::where('zone_id', $value)
                                            ->where('id', '!=', $get('id'))
                                            ->first();
                                        if ($existingAssignment) {
                                            $fail("This zone is already assigned to center: {$existingAssignment->center_id}. A zone cannot have multiple centers.");
                                        }
                                    };
                                },
                            ]),
                        Forms\Components\TextInput::make('center_id')
                            ->required()
                            ->label('Center Name')
                            ->maxLength(255)
                            // ->disabled(fn (Forms\Get $get) => ZoneAssignment::where('zone_id', $get('zone_id'))->exists())
                            ->dehydrated(),
                        // ->rules([
                        //     function (Forms\Get $get) {
                        //         return function (string $attribute, $value, Closure $fail) use ($get) {
                        //             $centerAssignment = ZoneAssignment::where('center_id', $value)
                        //                 ->where('zone_id', '!=', $get('zone_id'))
                        //                 ->where('id', '!=', $get('id'))
                        //                 ->first();

                        //             if ($centerAssignment) {
                        //                 $fail("This center is already assigned to zone: {$centerAssignment->zone->name}. A center cannot be assigned to multiple zones.");
                        //             }
                        //         };
                        //     },
                        // ]),
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->format('Y-m-d')
                            ->displayFormat('d F Y')
                            ->minDate(Carbon::today())
                            ->afterOrEqual(now()->startOfDay())
                            ->reactive(),

                        Forms\Components\Repeater::make('time_slots')
                            ->label('Time Slots')
                            ->schema([
                                Forms\Components\TimePicker::make('time')
                                    ->label('Time')
                                    ->required()
                                    ->format('h:i A')
                                    ->reactive()
                                    ->afterOrEqual(function (callable $get) {
                                        $date = $get('../../date');
                                        if ($date && Carbon::parse($date)->isToday()) {
                                            return now();
                                        }
                                        return '00:00';
                                    })
                                    ->rules([
                                        function (callable $get) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                $date = $get('../../date');
                                                if ($date && $value) {
                                                    $dateTime = Carbon::parse($date)->setTime(
                                                        Carbon::parse($value)->hour,
                                                        Carbon::parse($value)->minute
                                                    );

                                                    if ($dateTime->isPast()) {
                                                        $fail("The selected date and time must be in the future.");
                                                    }
                                                }
                                            };
                                        },
                                    ]),
                            ])
                            ->defaultItems(1)
                            ->minItems(1)
                            ->itemLabel(fn (array $state): ?string => 
                                $state['time'] 
                                    ? 'Time Slot: ' . Carbon::parse($state['time'])->format('h:i A')
                                    : 'New Time Slot'
                            )
                            ->addActionLabel('Add Time Slot')
                            ->deleteAction(
                                fn ($action) => $action->label('Remove Time Slot')
                            )
                            ->collapsible()
                            ->collapsed()
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->columnSpanFull(),

                        Forms\Components\TimePicker::make('time')
                            ->label('Time (Legacy)')
                            ->format('h:i A')
                            ->reactive()
                            ->visible(fn ($record) => $record && $record->time && !$record->time_slots)
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('timezone')
                            ->label('Timezone')
                            ->required()
                            ->default('IST')
                            ->maxLength(64)
                            ->dehydrateStateUsing(fn ($state) => $state ? strtoupper($state) : null)
                    ])
                    ->columns(2)
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('zone.name')
                    ->label('Zone')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('center_id')
                    ->label('Center Name')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('date')
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)->format('M d, Y - l');
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_slots')
                    ->label('Time Slots')
                    ->formatStateUsing(function ($state, ZoneAssignment $record) {
                        if (!empty($state) && is_array($state)) {
                            $timeSlots = array_map(function ($slot) use ($record) {
                                $time = $slot['time'] ?? null;
                                if ($time) {
                                    $formattedTime = Carbon::parse($time)->format('h:i A');
                                    return $record->timezone
                                        ? $formattedTime . ' ' . strtoupper($record->timezone)
                                        : $formattedTime;
                                }
                                return null;
                            }, $state);
                            return implode(', ', array_filter($timeSlots));
                        }
                        
                        // Fallback to legacy time field
                        if ($record->time) {
                            $formattedTime = Carbon::parse($record->time)->format('h:i A');
                            return $record->timezone
                                ? $formattedTime . ' ' . strtoupper($record->timezone)
                                : $formattedTime;
                        }
                        
                        return 'N/A';
                    })
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->wrap(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('zone')
                    ->relationship('zone', 'name'),
                // DateFilter::make('date'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->icon('heroicon-o-eye'),
                Tables\Actions\EditAction::make()->icon('heroicon-o-pencil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->icon('heroicon-o-trash'),
            ])
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZoneAssignments::route('/'),
            'create' => Pages\CreateZoneAssignment::route('/create'),
            'edit' => Pages\EditZoneAssignment::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ZoneApplicationStats::class,
        ];
    }
}
