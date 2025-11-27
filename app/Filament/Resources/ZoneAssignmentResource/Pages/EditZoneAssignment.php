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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure time_slots is properly formatted as an array for the repeater
        if (isset($data['time_slots'])) {
            if (is_string($data['time_slots'])) {
                $data['time_slots'] = json_decode($data['time_slots'], true) ?? [];
            }
            if (!is_array($data['time_slots'])) {
                $data['time_slots'] = [];
            }
        } else {
            $data['time_slots'] = [];
        }
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure time_slots is properly formatted before saving
        if (isset($data['time_slots']) && is_array($data['time_slots'])) {
            // Filter out any empty slots
            $data['time_slots'] = array_values(array_filter($data['time_slots'], function ($slot) {
                return !empty($slot['time']);
            }));
        } else {
            $data['time_slots'] = [];
        }
        
        return $data;
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
            ->orderBy('id')
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

        // Calculate distribution: split applicants equally across time slots
        $totalApplicants = $approvedApplicants->count();
        $totalTimeSlots = count($timeSlotValues);
        
        // Calculate how many applicants per slot (rounded up for first slots if needed)
        $baseApplicantsPerSlot = (int) floor($totalApplicants / $totalTimeSlots);
        $extraApplicants = $totalApplicants % $totalTimeSlots;
        
        // Distribute applicants evenly across time slots
        $applicantIndex = 0;
        
        foreach ($timeSlotValues as $slotIndex => $timeSlot) {
            // Calculate how many applicants should be in this slot
            // First few slots get one extra applicant if there's a remainder
            $applicantsInThisSlot = $baseApplicantsPerSlot + ($slotIndex < $extraApplicants ? 1 : 0);
            
            // Assign this time slot to the calculated number of applicants
            for ($i = 0; $i < $applicantsInThisSlot && $applicantIndex < $totalApplicants; $i++) {
                $applicant = $approvedApplicants[$applicantIndex];
                $applicant->time_slot = $timeSlot;
                $applicant->save();
                $applicantIndex++;
            }
        }
    }
}
