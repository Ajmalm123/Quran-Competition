<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ZoneApplicationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $zoneCounts = Application::select('zone', DB::raw('count(*) as count'))
            ->groupBy('zone')
            ->pluck('count', 'zone')
            ->toArray();

        $allZones = ['Kollam', 'Ernakulam', 'Malappuram', 'Kannur', 'Jeddah', 'Dubai', 'Doha', 'Bahrain', 'Muscat', 'Kuwait'];

        return collect($allZones)->map(function ($zone) use ($zoneCounts) {
            return Stat::make($zone, $zoneCounts[$zone] ?? 0)
                ->description('Applications')
                ->color('primary');
        })->toArray();
    }
}
