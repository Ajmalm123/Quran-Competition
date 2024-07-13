<?php

namespace App\Filament\Zone\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Jobs\SendEmailJob;
use Filament\Tables\Table;
use App\Models\Application;
use Maatwebsite\Excel\Facades\Excel;
use App\Filament\Notification;
use Filament\Resources\Resource;
use App\Exports\ApplicationExport;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Zone\Resources\ApplicationResource\Pages;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Your form schema here
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('passport_size_photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->label('Photo'),
                TextColumn::make('application_id')
                    ->searchable()
                    ->copyable()
                    ->label('Application ID'),
                TextColumn::make('full_name')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('contact_number')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone'),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                TextColumn::make('district')
                    ->searchable(),
                TextColumn::make('age')
                    ->label('Age')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('date_of_birth', $direction === 'desc' ? 'asc' : 'desc');
                    })
                    ->getStateUsing(fn($record) => Carbon::parse($record->date_of_birth)->age),
                    BadgeColumn::make('admit_status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Admitted',
                        'danger' => 'Declined',
                        'gray' => 'Absent'
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'Pending',
                        'heroicon-o-check-circle' => 'Admitted',
                        'heroicon-o-x-circle' => 'Declined',
                        'heroicon-o-x-mark' => 'Absent'
                    ])
                    ->sortable(),
                // TextColumn::make('whatsapp')
                //     ->label('WhatsApp')
                //     ->searchable()
                //     ->copyable()
                //     ->icon('heroicon-o-chat-bubble-left-ellipsis'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('district')
                    ->options(Application::DISTRICT)
                    ->label('District')
                    ->indicator('District'),
                SelectFilter::make('admit_status')
                    ->options([
                        'Pending' => 'Pending',
                        'Admitted' => 'Admitted',
                        'Declined' => 'Declined',
                        'Absent' => 'Absent'
                    ])
                    ->indicator('Admit Status'),
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
            ])
            ->filtersFormColumns(3)
            ->actions([
                // Action::make('admit')
                //     ->icon('heroicon-o-check')
                //     ->color('success')
                //     ->requiresConfirmation()
                //     ->action(fn(Application $record) => $record->update(['admit_status' => 'Admitted']))
                //     ->visible(fn(Application $record) => $record->admit_status !== 'Admitted'),
                // Action::make('decline')
                //     ->icon('heroicon-o-x-mark')
                //     ->color('danger')
                //     ->requiresConfirmation()
                //     ->action(fn(Application $record) => $record->update(['admit_status' => 'Declined']))
                //     ->visible(fn(Application $record) => $record->admit_status !== 'Declined'),
                // Action::make('mark_absent')
                //     ->icon('heroicon-o-exclamation-triangle')
                //     ->color('warning')
                //     ->requiresConfirmation()
                //     ->action(fn(Application $record) => $record->update(['admit_status' => 'Absent']))
                //     ->visible(fn(Application $record) => $record->admit_status !== 'Absent'),
                Action::make('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(
                        fn(Application $record) =>
                        'https://wa.me/' . preg_replace('/^0+/', '', preg_replace('/\D/', '', $record->contact_number)) .
                        '?text=' . urlencode('Your pre-filled message here'),
                        true
                    ),
                Tables\Actions\ViewAction::make()->icon('heroicon-o-eye'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('export')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            return Excel::download(new ApplicationExport($records), 'Applications.xlsx');
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('admit')
                        ->label('Admit Selected')
                        ->icon('heroicon-o-check')
                        ->action(fn(Collection $records) => $records->each->update(['admit_status' => 'Admitted']))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('decline')
                        ->label('Decline Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn(Collection $records) => $records->each->update(['admit_status' => 'Declined']))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('mark_absent')
                        ->label('Mark Selected as Absent')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->action(fn(Collection $records) => $records->each->update(['admit_status' => 'Absent']))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ])
            ])
            ->modifyQueryUsing(
                fn(Builder $query) => $query
                    ->where('zone_id', auth()->id())
                    ->where('status', 'Approved')
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
            'index' => Pages\ListApplications::route('/'),
            'view' => Pages\ViewApplication::route('/{record}/view'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}