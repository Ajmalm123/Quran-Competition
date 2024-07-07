<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ApplicationResource\Pages\ListApplications;
use App\Models\Application;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = '15s';
    protected int | string | array $columnSpan = 'full';

    protected static ?int $columns = 4;



    protected function getTablePage(): string
    {
        return ListApplications::class;
    }

    protected function getStats(): array
    {
        $totalCount = Application::count();
        $approvedCount = Application::where('status', 'Approved')->count();
        $rejectedCount = Application::where('status', 'Rejected')->count();
        $withheldCount = Application::where('status', 'withheld')->count();
        $createdCount = Application::where('status', 'Created')->count();

        $latestApplication = Application::latest()->first();
        $topDistrict = Application::select('district', DB::raw('count(*) as total'))
            ->groupBy('district')
            ->orderByDesc('total')
            ->first();
        $topZone = Application::select('zone', DB::raw('count(*) as total'))
            ->groupBy('zone')
            ->orderByDesc('total')
            ->first();

        return [
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
                    'tableFilters[status][value]' => 'withheld'
                ])),

            Stat::make('Pending Review', $createdCount)
                ->description($createdCount > 0 ? number_format(($createdCount / $totalCount) * 100, 1) . '% of total' : 'No pending applications')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray')
                ->url(route('filament.admin.resources.applications.index', [
                    'tableFilters[status][value]' => 'Created'
                ])),

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
            // Stat::make('Top Zone', $topZone ? $topZone->zone : 'N/A')
            //     ->description($topZone ? $topZone->total . ' applications' : 'No data available')
            //     ->descriptionIcon('heroicon-m-map')
            //     ->color('primary')
            //     ->url($topZone
            //         ? route('filament.admin.resources.applications.index', [
            //             'tableFilters[zone][value]' => $topZone->zone
            //         ])
            //         : null),
        ];
    }
}