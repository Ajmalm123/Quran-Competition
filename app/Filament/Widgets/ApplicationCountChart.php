<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Application;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class ApplicationCountChart extends ChartWidget
{
    protected static ?string $heading = 'Application Count';
    protected static string $color = 'info';
    protected int |string|array $columnSpan = 1;
    protected static ?string $maxHeight = '400px';
    protected static ?int $sort=3;


    protected function getData(): array
    {
        $data = Trend::model(Application::class)
        ->between(
            start: now()->startOfMonth(),
            end: now()->endOfMonth(),
        )
        ->perDay()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Application Recieved',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
