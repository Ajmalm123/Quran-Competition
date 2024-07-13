<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\Zone;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ApplicationCountChart extends ChartWidget
{
    protected static ?string $heading = 'Application Count by Zone';
    protected static string $color = 'info';
    protected int|string|array $columnSpan = 1;
    protected static ?string $maxHeight = '400px';
    protected static ?int $sort = 3;
    protected static ?array $options = [
        'aspectRatio' => 1,
        'maintainAspectRatio' => true,
    ];

    protected function getData(): array
    {
        $data = Application::select('zones.name as zone', DB::raw('count(*) as count'))
            ->join('zones', 'applications.zone_id', '=', 'zones.id')
            ->groupBy('zones.id', 'zones.name')
            ->get()
            ->pluck('count', 'zone')
            ->toArray();

        $zones = Zone::pluck('name', 'id')->toArray();

        // Ensure all zones are represented, even if they have zero count
        $counts = array_map(function ($zone) use ($data) {
            return $data[$zone] ?? 0;
        }, $zones);

        return [
            'datasets' => [
                [
                    'label' => 'Applications Received',
                    'data' => array_values($counts),
                ],
            ],
            'labels' => array_values($zones),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}