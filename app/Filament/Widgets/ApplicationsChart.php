<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use Filament\Widgets\ChartWidget;

class ApplicationsChart extends ChartWidget
{
    protected static ?string $heading = 'Applications Overview';
    protected int|string|array $columnSpan = '1/2'; // Half width


    protected static ?string $maxHeight = '400px';
    protected static ?int $sort = 2;

    protected static ?array $options = [
        'scales' => [
            'x' => [
                'display' => false,
            ],
            'y' => [
                'display' => false,
            ],
        ],
        'aspectRatio' => 1, // This will make the chart square
        'maintainAspectRatio' => true,
    ];

    protected function getData(): array
    {
        $approvedCount = Application::where('status', 'Approved')->count();
        $rejectedCount = Application::where('status', 'Rejected')->count();
        $withheldCount = Application::where('status', 'Withheld')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Application Status',
                    'data' => [$rejectedCount, $approvedCount, $withheldCount],
                    'backgroundColor' => [
                        'rgb(255, 99, 132)', // Red
                        'rgb(75, 192, 192)', // Green
                        'rgb(255, 205, 86)', // Yellow
                    ],
                ],
            ],
            'labels' => ['Rejected', 'Approved', 'Withheld'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
