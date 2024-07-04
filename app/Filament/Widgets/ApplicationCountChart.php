<?php

namespace App\Filament\Widgets;

use App\Models\Application;
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

    const ZONE = [
        'Kollam' => 'Kollam',
        'Ernakulam' => 'Ernakulam',
        'Malappuram' => 'Malappuram',
        'Kannur' => 'Kannur',
        'Jeddah' => 'Jeddah',
        'Dubai' => 'Dubai',
        'Doha' => 'Doha',
        'Bahrain' => 'Bahrain',
        'Muscat' => 'Muscat',
        'Kuwait' => 'Kuwait'
    ];

    protected function getData(): array
    {
        $data = Application::select('zone', DB::raw('count(*) as count'))
            ->whereIn('zone', array_keys(self::ZONE))
            ->groupBy('zone')
            ->get()
            ->pluck('count', 'zone')
            ->toArray();

        // Ensure all zones are represented, even if they have zero count
        $counts = array_map(function ($zone) use ($data) {
            return $data[$zone] ?? 0;
        }, array_keys(self::ZONE));

        return [
            'datasets' => [
                [
                    'label' => 'Applications Received',
                    'data' => array_values($counts),
                ],
            ],
            'labels' => array_keys(self::ZONE),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
