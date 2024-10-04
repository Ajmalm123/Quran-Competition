<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use Filament\Actions;
use App\Mail\BulkMail;
use App\Models\Application;
use Filament\Actions\Action;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ApplicationResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Notifications\Notification;



class ListApplications extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ApplicationResource::class;
    protected ?string $maxContentWidth = 'full';
    public $defaultAction = 'newApplicationsToday';

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make()
                ->label('Create Application')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    public function newApplicationsToday(): Actions\Action
    {
        $sessionKey = 'new_applications_modal_shown';
        $count = Session::get($sessionKey, 0);

        if ($count < 3) {
            Session::put($sessionKey, $count + 1);
            $newApplicationsToday = Application::query()->whereDate('created_at', today())->count();

            return Actions\Action::make('newApplicationsToday')
                ->visible($newApplicationsToday > 0)
                ->modalSubmitActionLabel('Got It!')
                ->color('success')
                ->modalCancelAction(false)
                ->modalHeading('New Applications Today')
                ->modalDescription(new HtmlString("Today so far got <strong>{$newApplicationsToday}</strong> new applications."))
                ->modalWidth('lg')
                ->action(null);
        }

        return Actions\Action::make('newApplicationsToday')->visible(false);
    }


}
