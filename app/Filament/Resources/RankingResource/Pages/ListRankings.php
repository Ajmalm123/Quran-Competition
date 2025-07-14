<?php

namespace App\Filament\Resources\RankingResource\Pages;

use App\Models\Category;
use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\RankingResource;

class ListRankings extends ListRecords
{
    protected static string $resource = RankingResource::class;

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
                return redirect()->to(route('filament.admin.resources.rankings.index'));
            });
        
        // Add category actions
        foreach ($categories as $category) {
            $actions[] = Action::make('category_' . $category->id)
                ->label($category->name)
                ->color($selectedCategory == $category->id ? 'primary' : 'gray')
                ->action(function () use ($category) {
                    Session::put('selected_category_filter', $category->id);
                    return redirect()->to(route('filament.admin.resources.rankings.index', [
                        'tableFilters[category_id][values]' => [$category->id]
                    ]));
                });
        }
        
        return $actions;
    }
}