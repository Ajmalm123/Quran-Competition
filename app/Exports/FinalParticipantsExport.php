<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\ParticipantPerCategorySheet;

class FinalParticipantsExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $categories = Category::where('is_active', true)->get();

        foreach ($categories as $category) {
            $sheets[] = new ParticipantPerCategorySheet($category);
        }

        return $sheets;
    }
}
