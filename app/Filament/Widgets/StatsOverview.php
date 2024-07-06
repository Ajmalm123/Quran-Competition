<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ApplicationResource\Pages\ListApplications;
use App\Models\Application;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;
    protected function getTablePage(): string
    {
        return ListApplications::class;
    }
    protected function getStats(): array
    {
        $approvedCount = Application::where('status', 'Approved')->count();
        $rejectedCount = Application::where('status', 'Rejected')->count();
        $withheldCount = Application::where('status', 'withheld')->count();

        return [
            Stat::make('Total Application Received', Application::count())
                ->url(route('filament.admin.resources.applications.index')),
            Stat::make('Approved', $approvedCount)
                ->url(route('filament.admin.resources.applications.index', [
                    'tableFilters[status][value]' => 'Approved'
                ])),
            Stat::make('Rejected', $rejectedCount)
                ->url(route('filament.admin.resources.applications.index', [
                    'tableFilters[status][value]' => 'Rejected'
                ])),
            Stat::make('Withheld', $withheldCount)
                ->url(route('filament.admin.resources.applications.index', [
                    'tableFilters[status][value]' => 'withheld'
                ]))
        ];
    }
}
