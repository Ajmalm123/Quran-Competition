<?php

namespace App\Filament\Resources\ZoneAssignmentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ZoneAssignmentResource;
use App\Filament\Widgets\ZoneApplicationStatsWidget;

class ListZoneAssignments extends ListRecords
{
    protected static string $resource = ZoneAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            ZoneApplicationStatsWidget::class,
        ];
    }
}
