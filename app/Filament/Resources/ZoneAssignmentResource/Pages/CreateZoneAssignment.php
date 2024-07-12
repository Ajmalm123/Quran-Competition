<?php

namespace App\Filament\Resources\ZoneAssignmentResource\Pages;

use Closure;
use Carbon\Carbon;
use Filament\Forms;
use App\Models\Zone;
use App\Models\ZoneAssignment;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ZoneAssignmentResource;

class CreateZoneAssignment extends CreateRecord
{
    protected static string $resource = ZoneAssignmentResource::class;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Zone Assignments')
                    ->description('Assign centers to zones with dates and times.')
                    ->icon('heroicon-o-map')
                    ->schema([
                        Forms\Components\Repeater::make('zone_assignments')
                            ->schema([
                                Forms\Components\Select::make('zone_id')
                                    ->label('Zone')
                                    ->options(Zone::pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('center_id')
                                    ->required()
                                    ->label('Center Name')
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('date')
                                    ->required()
                                    ->format('Y-m-d')
                                    ->displayFormat('d F Y')
                                    ->minDate(Carbon::today())
                                    ->afterOrEqual(now()->startOfDay())
                                    ->reactive(),

                                Forms\Components\TimePicker::make('time')
                                    ->required()
                                    ->format('h:i A')
                                    ->reactive()
                                    ->afterOrEqual(function (callable $get) {
                                        $date = $get('date');
                                        if ($date && Carbon::parse($date)->isToday()) {
                                            return now();
                                        }
                                        return '00:00';
                                    })
                                    ->rules([
                                        function (callable $get) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                $date = Carbon::parse($get('date'));
                                                $time = Carbon::parse($value);
                                                $dateTime = $date->setTime($time->hour, $time->minute);

                                                if ($dateTime->isPast()) {
                                                    $fail("The selected date and time must be in the future.");
                                                }
                                            };
                                        },
                                    ]),
                            ])

                            ->columns([
                                'sm' => 2,
                                'lg' => 4,
                            ])
                            ->itemLabel(function (array $state): ?string {
                                $zoneName = Zone::find($state['zone_id'])->name ?? 'Unknown Zone';
                                return "{$zoneName} - {$state['center_id']}";
                            })
                            ->collapsible()
                            ->reorderableWithButtons()
                            ->defaultItems(1)
                            ->createItemButtonLabel('Add New Zone Assignment')
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, Closure $fail) {
                                        $zoneAssignments = collect($value);
                                        $existingAssignments = ZoneAssignment::all();
                                        $zonesWithMultipleCenters = $zoneAssignments
                                            ->groupBy('zone_id')
                                            ->filter(function ($assignments) {
                                                return $assignments->unique('center_id')->count() > 1;
                                            });
                                        if ($zonesWithMultipleCenters->isNotEmpty()) {
                                            $fail("A zone cannot have multiple centers. Please check your assignments.");
                                        }
                                        $duplicateAssignments = $zoneAssignments
                                            ->groupBy(function ($assignment) {
                                                return $assignment['zone_id'] . '-' . $assignment['center_id'];
                                            })
                                            ->filter(function ($group) {
                                                return $group->count() > 1;
                                            });
                                        if ($duplicateAssignments->isNotEmpty()) {
                                            $fail("Duplicate zone-center assignments are not allowed.");
                                        }
                                        foreach ($zoneAssignments as $assignment) {
                                            $duplicate = $existingAssignments->where('zone_id', $assignment['zone_id'])
                                                ->count();
                                            if ($duplicate >= 1) {
                                                $fail("A zone cannot have multiple centers. Please check your assignments.");
                                            }
                                        }
                                    };
                                },
                            ])
                    ])
                    ->columnSpan('full')
            ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $zoneAssignments = $data['zone_assignments'];
        $createdAssignments = collect();

        foreach ($zoneAssignments as $assignment) {
            $createdAssignments->push(ZoneAssignment::create($assignment));
        }

        // Return the first created assignment as the "main" record
        return $createdAssignments->first();
    }

    protected function getRedirectUrl(): string
    {
        return ZoneAssignmentResource::getUrl('index');
    }
}
