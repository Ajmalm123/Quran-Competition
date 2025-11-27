<?php

namespace App\Filament\Resources\ZoneAssignmentResource\Pages;

use App\Filament\Resources\ZoneAssignmentResource;
use App\Models\Application;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Section;

class EditZoneAssignment extends EditRecord
{
    protected static string $resource = ZoneAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return ZoneAssignmentResource::getUrl('index');
    }

    protected function afterSave(): void
    {
        $zoneAssignment = $this->record;
        
        // Get time slots from the saved record
        $timeSlots = $zoneAssignment->time_slots;
        
        // Only proceed if time slots exist
        if (empty($timeSlots) || !is_array($timeSlots)) {
            return;
        }

        // Get approved applicants for this zone
        $approvedApplicants = Application::where('zone_id', $zoneAssignment->zone_id)
            ->where('status', 'Approved')
            ->get();

        if ($approvedApplicants->isEmpty()) {
            return;
        }

        // Extract time values from time slots array and format them
        $timeSlotValues = [];
        foreach ($timeSlots as $slot) {
            if (isset($slot['time']) && !empty($slot['time'])) {
                $timeSlotValues[] = Carbon::parse($slot['time'])->format('h:i A');
            }
        }
        
        if (empty($timeSlotValues)) {
            return;
        }

        // Distribute applicants equally across time slots using round-robin
        $totalApplicants = $approvedApplicants->count();
        $totalTimeSlots = count($timeSlotValues);
        $timeSlotIndex = 0;
        
        foreach ($approvedApplicants as $index => $applicant) {
            // Use round-robin to assign time slots
            $assignedTimeSlot = $timeSlotValues[$timeSlotIndex % $totalTimeSlots];
            
            // Assign time slot to applicant
            $applicant->time_slot = $assignedTimeSlot;
            $applicant->save();
            
            $timeSlotIndex++;
        }
    }
}
