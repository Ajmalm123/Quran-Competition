<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Filament\Actions;
use App\Mail\BulkMail;
use App\Models\Application;
use App\Models\Category;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ApplicationResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Notifications\Notification;



class ListApplications extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';

    protected function getHeaderActions(): array
    {
        $categories = Category::where('is_active', true)->get();
        
        // Get the current category filter from URL parameters first, then fall back to session
        $request = request();
        $categoryFilter = $request->get('tableFilters.category_id.values');
        $selectedCategory = null;
        
        if ($categoryFilter && is_array($categoryFilter) && count($categoryFilter) === 1) {
            $selectedCategory = (int) $categoryFilter[0];
        } elseif ($categoryFilter && !is_array($categoryFilter)) {
            // Handle single value case
            $selectedCategory = (int) $categoryFilter;
        } else {
            $selectedCategory = Session::get('selected_category_filter') ? (int) Session::get('selected_category_filter') : null;
        }
        
        \Log::info('Selected Category Debug', [
            'categoryFilter' => $categoryFilter,
            'selectedCategory' => $selectedCategory,
            'selectedCategoryType' => gettype($selectedCategory),
            'sessionValue' => Session::get('selected_category_filter')
        ]);
        

        
        $actions = [];
        
        // Add year selector action
        // $currentYear = Session::get('dashboard_selected_year', now()->year);
        // $actions[] = Action::make('yearSelector')
        //     ->label('Year: ' . $currentYear)
        //     ->icon('heroicon-o-calendar')
        //     ->color('info')
        //     ->form([
        //         \Filament\Forms\Components\Select::make('year')
        //             ->label('Select Year')
        //             ->options(function () {
        //                 $years = [];
        //                 $currentYear = now()->year;
        //                 for ($i = $currentYear; $i >= 2000; $i--) {
        //                     $years[$i] = $i;
        //                 }
        //                 return $years;
        //             })
        //             ->default($currentYear)
        //             ->required(),
        //     ])
        //     ->action(function (array $data) {
        //         Session::put('dashboard_selected_year', $data['year']);
        //         \Log::info('Year selector changed', [
        //             'selected_year' => $data['year'],
        //             'session_value' => Session::get('dashboard_selected_year')
        //         ]);
        //         Notification::make()
        //             ->title('Year filter updated to ' . $data['year'])
        //             ->success()
        //             ->send();
        //         return redirect()->to(route('filament.admin.resources.applications.index'));
        //     });
        
        // Add "All Categories" action
        $allCategoriesColor = $selectedCategory !== null ? 'gray' : 'primary';
        $actions[] = Action::make('allCategories')
            ->label('All Categories')
            ->color($allCategoriesColor)
            ->size('sm')
            ->extraAttributes(['style' => 'background-color: ' . ($selectedCategory !== null ? '#6b7280' : '#3b82f6') . '; border-color: ' . ($selectedCategory !== null ? '#6b7280' : '#3b82f6') . ';'])
            ->action(function () {
                Session::forget('selected_category_filter');
                return redirect()->to(route('filament.admin.resources.applications.index'));
            });
        
        foreach ($categories as $category) {
            $categoryColor = $selectedCategory === (int) $category->id ? 'primary' : 'gray';
            $isSelected = $selectedCategory === (int) $category->id;

            $actions[] = Action::make('category_' . $category->id)
                ->label($category->name)
                ->color($categoryColor)
                ->size('sm')
                ->extraAttributes(['style' => 'background-color: ' . ($isSelected ? '#3b82f6' : '#6b7280') . '; border-color: ' . ($isSelected ? '#3b82f6' : '#6b7280') . ';'])
                ->action(function () use ($category) {
                    Session::put('selected_category_filter', $category->id);
                    return redirect()->to(route('filament.admin.resources.applications.index', [
                        'tableFilters[category_id][values]' => [$category->id]
                    ]));
                });
        }
        
        // Add Create Action
        $actions[] = Actions\CreateAction::make()
            ->label('Create Application')
            ->icon('heroicon-o-plus-circle');
        
        return $actions;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Widgets\YearFilterWidget::class,
            // \App\Filament\Widgets\CategoryFilterButtonsWidget::class,
        ];
    }



    // Override the mount method to handle URL parameters and sync with session
    public function mount(): void
    {
        parent::mount();
        
        // Check if there's a category filter in the URL and sync with session
        $request = request();
        $categoryFilter = $request->get('tableFilters.category_id.values');
        
        if ($categoryFilter && is_array($categoryFilter) && count($categoryFilter) === 1) {
            Session::put('selected_category_filter', (int) $categoryFilter[0]);
        } elseif ($categoryFilter && !is_array($categoryFilter)) {
            // Handle single value case
            Session::put('selected_category_filter', (int) $categoryFilter);
        } elseif (!$categoryFilter) {
            // If no category filter in URL, clear session
            Session::forget('selected_category_filter');
        }
    }
}
