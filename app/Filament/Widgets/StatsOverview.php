<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $approvedCount = Application::where('status', 'Approved')->count();
        $rejectedCount = Application::where('status', 'Rejected')->count();
        $withheldCount = Application::where('status', 'withheld')->count();
        $statusText = "Approved: $approvedCount\nRejected: $rejectedCount\nWithheld: $withheldCount";

        return [
            Stat::make('Total Application Recieved',Application::count()),
            Stat::make('Approved', $approvedCount),
            Stat::make('Rejected', $rejectedCount),
            Stat::make('Withheld', $withheldCount)
        ];
    }
}
