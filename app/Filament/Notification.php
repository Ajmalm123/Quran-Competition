<?php

namespace App\Filament;

use Closure;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Arr;
use Filament\Notifications\Actions\ActionGroup;
use Filament\Notifications\Notification as BaseNotification;

class Notification extends BaseNotification
{

    /**
     * @param array< Action | ActionGroup> |ActionGroup | Closure $actions
     */

    public function actions(array|ActionGroup|Closure $actions): static
    {
        $this->actions = Arr::prepend(
            $actions,
            Action::make('goToDashboard')->label('Go To Dashboard')
                ->url(fn() => route('filament.admin.pages.dashboard'))
        );
        return $this;
    }
}
