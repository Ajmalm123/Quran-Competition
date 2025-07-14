<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\Category;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ApplicationsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Applications by Category';
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
        $year = (int) ($this->filters['year'] ?? now()->year);
        $start = now()->setYear($year)->startOfYear();
        $end = now()->setYear($year)->endOfYear();

        $categories = Category::where('is_active', true)->get();
        $data = [];
        $labels = [];
        $colors = [];

        foreach ($categories as $category) {
            $count = Application::where('category_id', $category->id)
                ->whereBetween('created_at', [$start, $end])
                ->count();
            $data[] = $count;
            $labels[] = $category->name;
            
            // Assign colors based on gender restriction
            $colors[] = $this->getColorForCategory($category->gender_restriction);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Applications by Category',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function getColorForCategory($genderRestriction): string
    {
        return match ($genderRestriction) {
            'Male' => 'rgb(54, 162, 235)', // Blue
            'Female' => 'rgb(255, 99, 132)', // Red
            'Both' => 'rgb(75, 192, 192)', // Green
            default => 'rgb(201, 203, 207)', // Gray
        };
    }
}
