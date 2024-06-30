<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\ApplicationResource;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestApplications extends BaseWidget
{
    protected static ?int $sort=4;
    protected int |string|array $columnSpan = 'full';


    public function table(Table $table): Table
    {
        return $table
            ->query(ApplicationResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at','desc')
            ->columns([
                Tables\Columns\TextColumn::make('application_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district'),
                Tables\Columns\TextColumn::make('zone'),
                Tables\Columns\TextColumn::make('age')
                ->label('Age')
                ->sortable()
                ->getStateUsing(function ($record) {
                    return Carbon::parse($record->date_of_birth)->age;
                }),
                Tables\Columns\TextColumn::make('status')->badge()->color(function (string $state): string {
                    return match ($state) {
                        'Created' => 'grey',
                        'withheld' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger'
                    };
                })->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Created' => 'Created',
                        'withheld' => 'withheld',
                        'Approved' => 'Approved',
                        'Rejected'=>'Rejected'
                    ])
                    ]);
            // ->actions([
            //     Tables\Actions\ViewAction::make(),
            //     Tables\Actions\EditAction::make()
            // ]);
    }
}
