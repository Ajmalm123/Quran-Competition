<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'application_id' => $this->application_id,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'mother_tongue' => $this->mother_tongue,
            'educational_qualification' => $this->educational_qualification,
            'job' => $this->job,
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'district' => $this->district,
            'zone' => $this->zone->name,
            'institution_name' => $this->institution_name,
            'is_completed_ijazah' => $this->is_completed_ijazah,
            'qirath_with_ijazah' => $this->qirath_with_ijazah,
            'primary_competition_participation' => $this->primary_competition_participation,
            'passport_size_photo' => $this->getFileUrl('passport_size_photo'),
            'birth_certificate' => $this->getFileUrl('birth_certificate'),
            'letter_of_recommendation' => $this->getFileUrl('letter_of_recommendation'),
            'status' => $this->status,
            'admit_status' => $this->admit_status,
            'token_number' => $this->token_number,
        ];
    }

    private function getFileUrl($field)
    {
        // Ensure the disk is correctly configured in config/filesystems.php
        return $this->$field ? Storage::disk('public')->url($this->$field) : null;
    }
}
