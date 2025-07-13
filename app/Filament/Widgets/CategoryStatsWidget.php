<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Application;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CategoryStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $categories = Category::where('is_active', true)->get();
        $stats = [];

        foreach ($categories as $category) {
            $count = Application::where('category_id', $category->id)->count();
            
            $stats[] = Stat::make($category->name, $count)
                ->description('Applications')
                ->descriptionIcon('heroicon-m-users')
                ->color($this->getColorForCategory($category->gender_restriction))
                ->chart([7, 2, 10, 3, 15, 4, 17]);
        }

        // Add total applications stat
        $totalApplications = Application::count();
        $stats[] = Stat::make('Total Applications', $totalApplications)
            ->description('All Categories')
            ->descriptionIcon('heroicon-m-document-text')
            ->color('primary')
            ->chart([7, 2, 10, 3, 15, 4, 17]);

        return $stats;
    }

    private function getColorForCategory($genderRestriction): string
    {
        return match ($genderRestriction) {
            'Male' => 'success',
            'Female' => 'warning',
            'Both' => 'primary',
            default => 'gray',
        };
    }
} 