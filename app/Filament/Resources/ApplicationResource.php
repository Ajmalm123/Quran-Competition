<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Jobs\SendEmailJob;
use Filament\Tables\Table;
use App\Models\Application;
use Filament\Resources\Resource;
use App\Exports\ApplicationExport;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Columns\ImageColumn;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ApplicationResource\Pages;
use Webbingbrasil\FilamentAdvancedFilter\Filters\DateFilter;
use App\Filament\Resources\ApplicationResource\RelationManagers;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Application Details')
                    ->schema([
                        TextInput::make('full_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('date_of_birth')->label('Age')
                            ->required()->formatStateUsing(function ($record) {
                                return Carbon::parse($record->date_of_birth)->age;
                            }),
                        Select::make('gender')
                            ->required()
                            ->options(Application::GENDER),
                        Select::make('educational_qualification')
                            ->required()
                            ->options(Application::EDUCATION_QUALIFICATION),
                        TextInput::make('aadhar_number')
                            ->required()
                            ->maxLength(12),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        TextInput::make('contact_number')
                            ->required()
                            ->maxLength(15),
                        TextInput::make('whatsapp')
                            ->maxLength(15),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Textarea::make('c_address')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('pr_address')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('pincode')
                            ->maxLength(8),
                        Select::make('district')
                            ->required()
                            ->options(Application::DISTRICT),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Hifz and Participation Details')
                    ->schema([
                        Textarea::make('institution_name')
                            ->columnSpanFull(),
                        Select::make('is_completed_ijazah')
                            ->required()
                            ->options(Application::IS_COMPLETED_IJAZAH),
                        Textarea::make('qirath_with_ijazah')
                            ->columnSpanFull(),
                        Select::make('primary_competition_participation')
                            ->required()
                            ->options(Application::PRIMARY_COMPETITION_PARTICIPATION),
                        Select::make('zone')
                            ->required()
                            ->options(Application::ZONE),
                        Select::make('status')
                            ->required()
                            ->options(Application::STATUS),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Documents')
                    ->schema([
                        FileUpload::make('passport_size_photo')
                            ->disk('public')
                            ->directory('passport')
                            ->openable(),
                        FileUpload::make('birth_certificate')
                            ->disk('public')
                            ->directory('birth-certificate')
                            ->openable(),
                        FileUpload::make('letter_of_recommendation')
                            ->disk('public')
                            ->directory('letter-of-recommendation')
                            ->openable(),
                    ])
                    ->columns(1),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
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
                    ->dateTime('Y-m-d h:i A')
                    ->label('Created Date')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d h:i A')
                    ->label('Updated Date')
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
                    ]),
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('Send Mail')->icon('heroicon-o-envelope')->color('info')->form([
                    Forms\Components\TextInput::make('Subject')->label('Subject')->required(),
                    Forms\Components\Textarea::make('message')->label('Message')->required()
                ])->action(function (Application $application, array $data): void {
                    $dispatchData = [
                        'application' => $application,
                        'subject' => $data['Subject'],
                        'message' => $data['message'],
                    ];
                    // Dispatch the job
                    SendEmailJob::dispatch($dispatchData);
                    Notification::make()->title('Mail Send SuccessFully')->success()->send();
                }),
                Tables\Actions\DeleteAction::make(),
                // Action::make('Approve')->icon('heroicon-o-check')->requiresConfirmation()
                //     ->action(function ($data) {
                //         Notification::make()->title('Application Accepted')->success()->send();
                //     })->hidden(function (Application $application) {
                //         return  $application->full_name == 'Cedric Mayo';
                //     }),
                // Action::make('Reject')->icon('heroicon-o-x-mark')->requiresConfirmation()
                //     ->color('danger')
                //     ->action(function ($data) {
                //         Notification::make()->title('Application Rejected')->success()->send();
                //     })->hidden(function (Application $application) {
                //         return  $application->full_name == 'Cedric Mayao';
                //     }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('export')->label('Export to Excel')->icon('heroicon-o-document-arrow-down')->action(function (Collection $records) {
                        return Excel::download(new ApplicationExport($records), 'Applications.xlsx');
                    })
                ]),
            ]);
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
