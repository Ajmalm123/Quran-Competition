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
        $selectedCategory = Session::get('selected_category_filter');
        
        $actions = [];
        
        // Add "All Categories" action
        $actions[] = Action::make('allCategories')
            ->label('All Categories')
            ->color($selectedCategory ? 'gray' : 'primary')
            ->action(function () {
                Session::forget('selected_category_filter');
                return redirect()->to(route('filament.admin.resources.applications.index'));
            });
        
        // Add category actions
        foreach ($categories as $category) {
            $actions[] = Action::make('category_' . $category->id)
                ->label($category->name)
                ->color($selectedCategory == $category->id ? 'primary' : 'gray')
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


}
