<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Filament\Resources\ApplicationResource;
use App\Models\Application;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Session;

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
        // Session key to store the count of modal displays
        $sessionKey = 'new_applications_modal_shown';
        
        // Get the count from the session, defaulting to 0
        $count = Session::get($sessionKey, 0);
        
        // Check if the modal has been shown less than 2 times today
        if ($count < 2) {
            // Increment the count
            Session::put($sessionKey, $count + 1);
            
            $newApplicationsToday = Application::query()->whereDate('created_at', today())->count();
            
            return Actions\Action::make('newApplicationsToday')
                ->visible($newApplicationsToday > 0)
                ->modalSubmitActionLabel('Got It!')
                ->action(null)->color('success')
                ->modalCancelAction(false)
                ->modalHeading('New Applications Today')
                ->modalDescription(new HtmlString("Today so far got <strong>{$newApplicationsToday}</strong> new applications."));
        }

        // Return an empty action if the modal has already been shown 2 times
        return Actions\Action::make('newApplicationsToday')->visible(false);
    }
}
