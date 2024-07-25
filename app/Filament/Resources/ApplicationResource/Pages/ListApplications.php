<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Models\Application;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;

class ListApplications extends ListRecords
{

    use ExposesTableToWidgets;
    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';
    public $defaultAction = 'newApplicationsToday';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function newApplicationsToday(): Actions\Action
    {
        $newApplicationsToday = Application::query()->whereDate('created_at', today())->count();
        return Actions\Action::make('newApplicationsToday')
            ->visible($newApplicationsToday > 0)
            ->modalSubmitActionLabel('Got It!')
            ->action(null)->color('success')
            ->modalCancelAction(false)
            ->modalHeading('New Applications Today')
            ->modalDescription(new HtmlString("Today so far got <strong>{$newApplicationsToday}</strong> new applications."));
    }


}
