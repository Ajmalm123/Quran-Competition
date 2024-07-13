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
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
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
                ImageColumn::make('passport_size_photo')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png'))
                    ->width(40)
                    ->height(40),
                TextColumn::make('application_id')
                    ->label('Application ID')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('contact_number')
                    ->label('Contact Number')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone'),
                TextColumn::make('zone.name')
                    ->label('Zone')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('participation_position')
                    ->label('Position')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('marks')
                    ->label('Marks')
                    ->sortable()
                    ->searchable(),

            ])
            ->defaultSort('zone.name', 'asc')
            ->groups([
                Group::make('zone.name')
                    ->label('Zone')
                    ->collapsible()
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
                        'https://wa.me/' . preg_replace('/^0+/', '', preg_replace('/\D/', '', $record->contact_number)) .
                        '?text=' . urlencode('Your pre-filled message here'),
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
                            return Excel::download(new ApplicationExport($records), 'Applications.xlsx');
                        })
                        ->deselectRecordsAfterCompletion(),
                ])
            ])
            ->striped()
            ->modifyQueryUsing(
                fn(Builder $query) => $query
                    ->where('admit_status', 'Admitted')
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
            'create' => Pages\CreateParticipants::route('/create'),
            'edit' => Pages\EditParticipants::route('/{record}/edit'),
        ];
    }
}
