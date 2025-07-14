<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ApplicationResource\Pages\ListApplications;
use App\Models\Application;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageTable, InteractsWithPageFilters;

    // protected static ?string $pollingInterval = '15s';

    protected function getColumns(): int
    {
        // $count = count($this->getCachedStats());

        // if ($count < 3) {
        //     return 4;
        // }

        // if (($count % 3) !== 1) {
        //     return 4;
        // }

        return 4;
    }

    // protected int | string | array $columnSpan = 'full';

    // protected static ?int $columns = 4;

    /**
     * Add a year filter to the dashboard widget, aligned top right.
     */
    protected function filters(): array
    {
        $currentYear = now()->year;
        $years = range($currentYear, $currentYear - 10);
        $yearOptions = [];
        foreach ($years as $year) {
            $yearOptions[$year] = $year;
        }
        return [
            \Filament\Forms\Components\Select::make('year')
                ->label('Year')
                ->options($yearOptions)
                ->default($currentYear)
                ->native(false)
                ->columnSpanFull()
                ->searchable(false)
                ->required(),
        ];
    }

    protected function getTablePage(): string
    {
        return ListApplications::class;
    }

    protected function getCacheKey(): string
    {
        $year = (int) session('dashboard_selected_year', now()->year);
        return 'stats_overview_' . $year;
    }

    protected function getStats(): array
    {
        $year = now()->year;
        $start = now()->setYear($year)->startOfYear();
        $end = now()->setYear($year)->endOfYear();

        $lastYear = $year - 1;
        $lastYearStart = now()->setYear($lastYear)->startOfYear();
        $lastYearEnd = now()->setYear($lastYear)->endOfYear();
        $lastYearTotal = Application::whereBetween('created_at', [$lastYearStart, $lastYearEnd])->count();

        \Log::info('StatsOverview widget', [
            'session_year' => session('dashboard_selected_year'),
            'default_year' => now()->year,
            'used_year' => $year,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString()
        ]);

        $totalCount = Application::whereBetween('created_at', [$start, $end])->count();
        $approvedCount = Application::where('status', 'Approved')->whereBetween('created_at', [$start, $end])->count();
        $rejectedCount = Application::where('status', 'Rejected')->whereBetween('created_at', [$start, $end])->count();
        $withheldCount = Application::where('status', 'withheld')->whereBetween('created_at', [$start, $end])->count();
        $createdCount = Application::where('status', 'Created')->whereBetween('created_at', [$start, $end])->count();

        $latestApplication = Application::whereBetween('created_at', [$start, $end])->latest()->first();
        $topDistrict = Application::select('district', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('district')
            ->orderByDesc('total')
            ->first();
        $topZone = Application::select('zones.name as zone', DB::raw('count(*) as total'))
            ->join('zones', 'applications.zone_id', '=', 'zones.id')
            ->whereBetween('applications.created_at', [$start, $end])
            ->groupBy('zones.id', 'zones.name')
            ->orderByDesc('total')
            ->first();

        $stats = [
   
            Stat::make('Total Applications', $totalCount)
                ->description('All applications received')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->url(route('filament.admin.resources.applications.index')),

            Stat::make('Approved', $approvedCount)
                ->description($approvedCount > 0 ? number_format(($approvedCount / $totalCount) * 100, 1) . '% of total' : 'No approvals yet')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.applications.index', [
                    'tableFilters[status][value]' => 'Approved'
                ])),

            Stat::make('Rejected', $rejectedCount)
                ->description($rejectedCount > 0 ? number_format(($rejectedCount / $totalCount) * 100, 1) . '% of total' : 'No rejections yet')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger')
                ->url(route('filament.admin.resources.applications.index', [
                    'tableFilters[status][value]' => 'Rejected'
                ])),

            Stat::make('Withheld', $withheldCount)
                ->description($withheldCount > 0 ? number_format(($withheldCount / $totalCount) * 100, 1) . '% of total' : 'No withheld applications')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(route('filament.admin.resources.applications.index', [
                    'tableFilters[status][value]' => 'Withheld'
                ])),
                ...($year !== $lastYear ? [
                    Stat::make('Total Applications (Last Year)', $lastYearTotal)
                        ->description('Applications in ' . $lastYear)
                        ->descriptionIcon('heroicon-m-arrow-uturn-left')
                        ->color('secondary')
                        ->url(route('filament.admin.resources.applications.index', [
                            'tableFilters[created_at][from]' => $lastYearStart->toDateString(),
                            'tableFilters[created_at][until]' => $lastYearEnd->toDateString(),
                        ])),
                ] : []),
    

            Stat::make('Participants Ranking', Application::whereNotNull('marks')->whereBetween('created_at', [$start, $end])->count())
                ->description('View overall ranking')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success')
                ->url(route('filament.admin.resources.rankings.index')),

            Stat::make('Latest Application', $latestApplication ? $latestApplication->full_name : 'N/A')
                ->description($latestApplication ? 'Submitted on ' . $latestApplication->created_at->format('M d, Y') : 'No applications yet')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
                ->url($latestApplication
                    ? route('filament.admin.resources.applications.view', ['record' => $latestApplication->id])
                    : null),

            Stat::make('Top District', $topDistrict ? $topDistrict->district : 'N/A')
                ->description($topDistrict ? $topDistrict->total . ' applications' : 'No data available')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success')
                ->url($topDistrict
                    ? route('filament.admin.resources.applications.index', [
                        'tableFilters[district][value]' => $topDistrict->district
                    ])
                    : null),
            Stat::make('Top Zone', $topZone ? $topZone->zone : 'N/A')
                ->description($topZone ? $topZone->total . ' applications' : 'No data available')
                ->descriptionIcon('heroicon-m-map')
                ->color('primary')
                ->url($topZone
                    ? route('filament.admin.resources.applications.index', [
                        'tableFilters[zone][value]' => $topZone->zone
                    ])
                    : null),
        ];
        return $stats;
    }
}
