<?php

namespace App\Filament\Resources\ZoneAssignmentResource\Widgets;

use App\Models\Application;
use App\Models\Zone;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ZoneApplicationStats extends BaseWidget
{
    protected function getStats(): array
    {
        $zoneCounts = Application::select('zone_id', DB::raw('count(*) as count'))
            ->groupBy('zone_id')
            ->pluck('count', 'zone_id')
            ->toArray();

        $zones = Zone::all();

        return $zones->map(function ($zone) use ($zoneCounts) {
            return Stat::make($zone->name, $zoneCounts[$zone->id] ?? 0)
                ->description('Applications')
                ->color('primary')
                ->url(route('filament.admin.resources.applications.index', ['tableFilters[zone][value]' => $zone->id]));
        })->toArray();
    }
}
