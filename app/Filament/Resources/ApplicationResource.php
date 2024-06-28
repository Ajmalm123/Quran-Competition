<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Application;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('application_id')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('full_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(Application::GENDER),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required(),
                Forms\Components\Select::make('mother_tongue')
                    ->required()
                    ->options(Application::MOTHERTONGUE),
                Forms\Components\Select::make('educational_qualification')
                    ->required()
                    ->options(Application::EDUCATION_QUALIFICATION),
                Forms\Components\TextInput::make('aadhar_number')
                    ->required()
                    ->maxLength(12),
                Forms\Components\TextInput::make('job')
                    ->maxLength(100),
                Forms\Components\TextInput::make('contact_number')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('whatsapp')
                    ->maxLength(15),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('c_address')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('pr_address')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('district')
                    ->required()
                    ->options(Application::DISTRICT),
                Forms\Components\TextInput::make('pincode')
                    ->required()
                    ->maxLength(10),
                Forms\Components\Textarea::make('institution_name')
                    ->columnSpanFull(),
                Forms\Components\Select::make('is_completed_ijazah')
                    ->required()
                    ->options(Application::IS_COMPLETED_IJAZAH),
                Forms\Components\Textarea::make('qirath_with_ijazah')
                    ->columnSpanFull(),
                Forms\Components\Select::make('primary_competition_participation')
                    ->required()
                    ->options(Application::PRIMARY_COMPETITION_PARTICIPATION),
                Forms\Components\Select::make('zone')
                    ->required()
                    ->options(Application::ZONE),
                FileUpload::make('passport_size_photo')->disk('public')->directory('passport'),
                FileUpload::make('birth_certificate')->disk('public')->directory('birth-certificate'),
                FileUpload::make('letter_of_recommendation')->disk('public')->directory('letter-of-recommendation'),
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
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('mother_tongue'),
                // Tables\Columns\TextColumn::make('educational_qualification'),
                // Tables\Columns\TextColumn::make('aadhar_number')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('job')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('contact_number')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('whatsapp')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district'),
                Tables\Columns\TextColumn::make('pincode')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('is_completed_ijazah'),
                // Tables\Columns\TextColumn::make('primary_competition_participation'),
                // Tables\Columns\TextColumn::make('zone'),
                // Tables\Columns\TextColumn::make('passport_size_photo')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('birth_certificate')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('letter_of_recommendation')
                // ->searchable(),
                // ImageColumn::make('passport_size_photo'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
                ->actions([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Action::make('approve')
                        ->action(function ($data) {})
                ])
            ->bulkActions([ 
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            // 'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
