<?php

namespace App\Filament\Zone\Resources\ParticipantsResource\Pages;

use App\Models\Category;
use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Zone\Resources\ParticipantsResource;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantsResource::class;
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
                return redirect()->to(route('filament.zone.resources.participants.index'));
            });
        
        // Add category actions
        foreach ($categories as $category) {
            $actions[] = Action::make('category_' . $category->id)
                ->label($category->name)
                ->color($selectedCategory == $category->id ? 'primary' : 'gray')
                ->action(function () use ($category) {
                    Session::put('selected_category_filter', $category->id);
                    return redirect()->to(route('filament.zone.resources.participants.index', [
                        'tableFilters[category_id][values]' => [$category->id]
                    ]));
                });
        }
        
        return $actions;
    }
}
