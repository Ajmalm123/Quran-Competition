<?php

namespace App\Filament\Resources;

use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    
    protected static ?string $navigationLabel = 'Categories';

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {   
        return $form
            ->schema([
                Section::make('Category Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter category name'),
                        
                        Select::make('gender_restriction')
                            ->required()
                            ->options(Category::GENDER_RESTRICTIONS)
                            ->default('Both')
                            ->hint('Select which gender can apply for this category'),
                        
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->hint('Enable or disable this category'),
                        
                        Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Enter category description (optional)')
                            ->maxLength(500),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('gender_restriction')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Both' => 'primary',
                        'Male' => 'success',
                        'Female' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Both' => 'heroicon-o-users',
                        'Male' => 'heroicon-o-user',
                        'Female' => 'heroicon-o-user',
                        default => 'heroicon-o-user',
                    }),
                
                ToggleColumn::make('is_active')
                    ->label('Active'),
                
                TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Applications')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => \App\Filament\Resources\CategoryResource\Pages\ListCategories::route('/'),
            'create' => \App\Filament\Resources\CategoryResource\Pages\CreateCategory::route('/create'),
            'edit' => \App\Filament\Resources\CategoryResource\Pages\EditCategory::route('/{record}/edit'),
        ];
    }
} 