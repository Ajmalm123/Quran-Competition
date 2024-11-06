<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Zone;
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
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ParticipantsResource\Pages;
use App\Filament\Resources\ParticipantsResource\RelationManagers;

class ParticipantsResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Final Participants';

    protected static ?string $pluralLabel = 'Final Participants';
    protected static ?int $navigationSort = 5;


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
                TextColumn::make('zone.name')
                    ->label('Zone')
                    ->sortable()
                    ->searchable()
                    ->description(fn ($record) => "Position {$record->participation_position}")
                    ->color('primary'),
                    
                BadgeColumn::make('participation_position')
                    ->label('Position')
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '2' => 'info', 
                        '3' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-o-trophy',
                        '2' => 'heroicon-o-star',
                        '3' => 'heroicon-o-hand-thumb-up',
                        default => 'heroicon-o-user',
                    }),

                TextColumn::make('marks')
                    ->label('Marks')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->alignCenter(),

                ImageColumn::make('passport_size_photo')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->width(40)
                    ->height(40),

                TextColumn::make('application_id')
                    ->label('Application ID')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Application ID copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),

                TextColumn::make('contact_number')
                    ->label('Contact Number')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),

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
            ->defaultSort('zone.name', 'asc')
            ->groups([
                Group::make('zone.name')
                    ->label('Zone')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false)
            ])
            ->filters([
                Filter::make('zone')
                    ->form([
                        Select::make('zone_id')
                            ->label('Zone')
                            ->options(Zone::pluck('name', 'id'))
                            ->searchable()
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['zone_id'],
                            fn(Builder $query, $zoneId): Builder => $query->where('zone_id', $zoneId)
                        );
                    })
                    ->persistent(),
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
                    ->persistent(),
                SelectFilter::make('admit_status')
                    ->options([
                        'Admitted' => 'Admitted',
                        'Completed' => 'Completed',
                    ])
                    ->indicator('Admit Status')
                    ->persistent(),
            ])
            ->filtersFormColumns(2)
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
                        Notification::make()->title('Mail Sent Successfully')->success()->withoutDashboardAction()
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
                ])
            ])
            ->defaultPaginationPageOption(3) // Show first 3 by default
            ->paginationPageOptions([3, 5, 10, 25, 50]) // Pagination options for "View More"
            ->poll('10s') // Auto refresh every 10 seconds
            ->striped()
            ->persistSortInSession()
            ->emptyStateHeading('No Participants Found')
            ->emptyStateDescription('Once participants are admitted and completed, they will appear here.')
            ->emptyStateIcon('heroicon-o-users');
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
            'create' => Pages\CreateParticipants::route('/create'),
            'edit' => Pages\EditParticipants::route('/{record}/edit'),
        ];
    }
}
