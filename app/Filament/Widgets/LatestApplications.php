<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Tables;
use App\Jobs\SendEmailJob;
use Filament\Tables\Table;
use App\Models\Application;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\ApplicationResource;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestApplications extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';


    public function table(Table $table): Table
    {
        return $table
            ->query(ApplicationResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
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
                        'Rejected' => 'Rejected'
                    ])
            ])
            ->actions([
                Tables\Actions\Action::make('Send Mail')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->form([
                        TextInput::make('Subject')->label('Subject')->required(),
                        Textarea::make('message')->label('Message')->required()
                    ])
                    ->action(function (Application $record, array $data): void {
                        $dispatchData = [
                            'page' => 'emails.send-mail',
                            'application' => $record,
                            'subject' => $data['Subject'],
                            'message' => $data['message'],
                        ];
                        // Dispatch the job
                        SendEmailJob::dispatch($dispatchData);
                        Notification::make()->title('Mail Sent Successfully')->success()->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->url(
                        fn(Application $record): string =>
                        ApplicationResource::getUrl('view', ['record' => $record])
                    ),
            ]);
    }
}
