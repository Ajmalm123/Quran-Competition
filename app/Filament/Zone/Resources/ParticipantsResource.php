<?php

namespace App\Filament\Zone\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Jobs\SendEmailJob;
use Filament\Tables\Table;
use App\Models\Application;
use App\Models\Participants;
use App\Filament\Notification;
use Filament\Resources\Resource;
use App\Exports\ApplicationExport;
use App\Exports\ParticipantExport;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Zone\Resources\ParticipantsResource\Pages;
use App\Filament\Zone\Resources\ParticipantsResource\RelationManagers;
use App\Exports\RankListExport;
use Filament\Forms\Components\Select;

class ParticipantsResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Participants';

    protected static ?string $pluralLabel = 'Participants';

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
                ImageColumn::make('passport_size_photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->label('Photo')
                    ->width(40)
                    ->height(40),
                TextColumn::make('application_id')
                    ->searchable()
                    ->copyable()
                    ->label('Application ID'),
                // ->font('mono'),
                TextColumn::make('full_name')
                    ->searchable()
                    ->weight('bold')
                    ->sortable()
                    ->wrap(),
                TextColumn::make('contact_number')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope')
                    ->wrap(),
                TextColumn::make('age')
                    ->label('Age')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('date_of_birth', $direction === 'desc' ? 'asc' : 'desc');
                    })
                    ->getStateUsing(fn($record) => Carbon::parse($record->date_of_birth)->age),

                TextInputColumn::make('marks')
                    ->type('number')
                    ->rules(['numeric', 'min:0', 'max:700'])
                    ->sortable()
                    ->alignCenter()
                    ->label('Marks')
                    ->width('150px')
                    ->updateStateUsing(function ($state, $record) {
                        DB::transaction(function () use ($state, $record) {
                            $record->marks = $state;
                            $record->save();

                            $zoneId = $record->zone_id;
                            $zoneApplications = Application::where('zone_id', $zoneId)
                                ->where('admit_status', 'Completed')
                                ->orderByDesc('marks')
                                ->get();
                            foreach ($zoneApplications as $index => $application) {
                                $position = $index + 1;
                                $application->participation_position = $position;
                                $application->save();
                            }
                        });

                        Notification::make()
                            ->title('Marks updated successfully')
                            ->success()
                            ->send();
                    }),

                TextInputColumn::make('token_number')
                    ->type('text')
                    ->rules(['string', 'max:255'])
                    ->sortable()
                    ->alignCenter()
                    ->label('Token Number')
                    ->updateStateUsing(function ($state, $record) {
                        DB::transaction(function () use ($state, $record) {
                            $record->token_number = $state;
                            $record->save();
                        });

                        Notification::make()
                            ->title('Token number updated successfully')
                            ->success()
                            ->send();
                    }),

                TextColumn::make('participation_position')
                    ->searchable()->label('Position'),
                BadgeColumn::make('admit_status')
                    ->colors([
                        'info' => 'Admitted',
                        'success' => 'Completed'
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Admitted',
                        'heroicon-o-check-badge' => 'Completed'
                    ])
                    ->sortable(),
            ])
            ->defaultSort('participation_position', 'asc')
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
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }
                        return $indicators;
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
                SelectFilter::make('admit_status')
                    ->options([
                        'Admitted' => 'Admitted',
                        'Completed' => 'Completed',
                    ])
                    ->indicator('Admit Status'),
            ])
            ->filtersFormColumns(1)
            ->actions([
                Action::make('Send Mail')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->form([
                        TextInput::make('Subject')->label('Subject')->required(),
                        Textarea::make('message')->label('Message')->required()
                    ])
                    ->action(function (Application $application, array $data): void {
                        $dispatchData = [
                            'page' => 'emails.send-mail',
                            'application' => $application,
                            'subject' => $data['Subject'],
                            'message' => $data['message'],
                            'mailer' => 'smtp'
                        ];
                        SendEmailJob::dispatch($dispatchData);
                        Notification::make()->title('Mail Sent Successfully')->success()
                            ->send();
                    }),
                Action::make('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(
                        fn(Application $record) =>
                        'https://wa.me/' . preg_replace('/^0+/', '', preg_replace('/\D/', '', $record->contact_number)),
                        true // This opens the link in a new tab
                    ),
                Tables\Actions\ViewAction::make()->icon('heroicon-m-eye'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('export')
                        ->label('Export to Excel')
                        ->icon('heroicon-m-document-arrow-down')
                        ->action(function (Collection $records) {
                            return Excel::download(new ParticipantExport($records), 'Participants.xlsx');
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('export_rank_list')
                        ->label('Export Rank List')
                        ->icon('heroicon-m-trophy')
                        ->action(function (Collection $records) {
                            return Excel::download(new RankListExport($records), 'RankList.xlsx');
                        })
                        ->deselectRecordsAfterCompletion(),
                ])
            ])
            ->striped()
            ->modifyQueryUsing(
                fn(Builder $query) => $query
                    ->where('zone_id', auth()->id())
                    ->whereIn('admit_status', values: ['Admitted', 'Completed'])
            );
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
            'index' => Pages\ListParticipants::route('/'),
            'view' => Pages\ViewParticipants::route('/{record}/view'),

            // 'create' => Pages\CreateParticipants::route('/create'),
            // 'edit' => Pages\EditParticipants::route('/{record}/edit'),
        ];
    }


}
