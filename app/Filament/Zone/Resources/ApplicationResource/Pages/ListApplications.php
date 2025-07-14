<?php

namespace App\Filament\Zone\Resources\ApplicationResource\Pages;

use App\Models\Category;
use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Zone\Resources\ApplicationResource;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ListApplications extends ListRecords
{
    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';


    protected function getHeaderActions(): array
    {
        $categories = Category::where('is_active', true)->get();
        $selectedCategory = Session::get('selected_category_filter');
        
        $actions = [];
        
        // Add year selector action
        $currentYear = Session::get('dashboard_selected_year', now()->year);
        $actions[] = Action::make('yearSelector')
            ->label('Year: ' . $currentYear)
            ->icon('heroicon-o-calendar')
            ->color('info')
            ->form([
                \Filament\Forms\Components\Select::make('year')
                    ->label('Select Year')
                    ->options(function () {
                        $years = [];
                        $currentYear = now()->year;
                        for ($i = $currentYear; $i >= 2000; $i--) {
                            $years[$i] = $i;
                        }
                        return $years;
                    })
                    ->default($currentYear)
                    ->required(),
            ])
            ->action(function (array $data) {
                Session::put('dashboard_selected_year', $data['year']);
                Log::info('Zone year selector changed', [
                    'selected_year' => $data['year'],
                    'session_value' => Session::get('dashboard_selected_year')
                ]);
                Notification::make()
                    ->title('Year filter updated to ' . $data['year'])
                    ->success()
                    ->send();
                return redirect()->to(route('filament.zone.resources.applications.index'));
            });
        
        // Add "All Categories" action
        $actions[] = Action::make('allCategories')
            ->label('All Categories')
            ->color($selectedCategory ? 'gray' : 'primary')
            ->action(function () {
                Session::forget('selected_category_filter');
                return redirect()->to(route('filament.zone.resources.applications.index'));
            });
        
        // Add category actions
        foreach ($categories as $category) {
            $actions[] = Action::make('category_' . $category->id)
                ->label($category->name)
                ->color($selectedCategory == $category->id ? 'primary' : 'gray')
                ->action(function () use ($category) {
                    Session::put('selected_category_filter', $category->id);
                    return redirect()->to(route('filament.zone.resources.applications.index', [
                        'tableFilters[category_id][values]' => [$category->id]
                    ]));
                });
        }
        
        return $actions;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Widgets\YearFilterWidget::class,
        ];
    }
}
