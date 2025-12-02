<?php

use App\Models\Application;
use App\Models\ZoneAssignment;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('redistribute-time-slots', function () {
    $this->info('Starting time slot redistribution for current year applications...');
    
    $currentYear = Carbon::now()->year;
    $startOfYear = Carbon::create($currentYear, 1, 1)->startOfDay();
    $endOfYear = Carbon::create($currentYear, 12, 31)->endOfDay();
    
    // Get all zone assignments that have time slots
    $zoneAssignments = ZoneAssignment::whereNotNull('time_slots')
        ->where('time_slots', '!=', '[]')
        ->where('time_slots', '!=', '')
        ->get();
    
    if ($zoneAssignments->isEmpty()) {
        $this->warn('No zone assignments with time slots found.');
        return;
    }
    
    $totalZonesProcessed = 0;
    $totalApplicantsAssigned = 0;
    
    foreach ($zoneAssignments as $zoneAssignment) {
        $timeSlots = $zoneAssignment->time_slots;
        
        // Ensure time_slots is an array
        if (is_string($timeSlots)) {
            $decoded = json_decode($timeSlots, true);
            $timeSlots = is_array($decoded) ? $decoded : [];
        }
        
        if (empty($timeSlots) || !is_array($timeSlots)) {
            continue;
        }
        
        // Get approved applicants for this zone created in the current year
        $approvedApplicants = Application::where('zone_id', $zoneAssignment->zone_id)
            ->where('status', 'Approved')
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->orderBy('id')
            ->get();
        
        if ($approvedApplicants->isEmpty()) {
            $this->line("Zone ID {$zoneAssignment->zone_id}: No approved applicants for current year.");
            continue;
        }
        
        // Extract time values from time slots array and format them
        $timeSlotValues = [];
        foreach ($timeSlots as $slot) {
            if (isset($slot['time']) && !empty($slot['time'])) {
                $timeSlotValues[] = Carbon::parse($slot['time'])->format('h:i A');
            }
        }
        
        if (empty($timeSlotValues)) {
            $this->warn("Zone ID {$zoneAssignment->zone_id}: No valid time slots found.");
            continue;
        }
        
        // Calculate distribution: split applicants equally across time slots
        $totalApplicants = $approvedApplicants->count();
        $totalTimeSlots = count($timeSlotValues);
        
        // Calculate how many applicants per slot (rounded up for first slots if needed)
        $baseApplicantsPerSlot = (int) floor($totalApplicants / $totalTimeSlots);
        $extraApplicants = $totalApplicants % $totalTimeSlots;
        
        // Distribute applicants evenly across time slots
        $applicantIndex = 0;
        $assignedCount = 0;
        
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
                $assignedCount++;
            }
        }
        
        $zoneName = $zoneAssignment->zone->name ?? "Zone ID {$zoneAssignment->zone_id}";
        $this->info("✓ {$zoneName}: Assigned {$assignedCount} applicants across {$totalTimeSlots} time slots");
        
        $totalZonesProcessed++;
        $totalApplicantsAssigned += $assignedCount;
    }
    
    $this->newLine();
    $this->info("Time slot redistribution completed!");
    $this->info("Total zones processed: {$totalZonesProcessed}");
    $this->info("Total applicants assigned: {$totalApplicantsAssigned}");
})->purpose('Redistribute existing time slots to approved applications created in the current year');
