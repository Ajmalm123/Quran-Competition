<?php

namespace App\Exports\Sheets;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParticipantPerCategorySheet implements FromQuery, WithTitle, WithHeadings, WithMapping
{
    private $category;
    private $rowNumber = 0;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function query()
    {
        return Application::query()
            ->whereIn('admit_status', ['Admitted', 'Completed'])
            ->where('category_id', $this->category->id)
            ->orderBy('participation_position', 'asc');
    }

    public function title(): string
    {
        return $this->category->name;
    }

    public function headings(): array
    {
        return [
            'Serial No',
            'Application ID',
            'Name',
            'District',
        ];
    }

    public function map($participant): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $participant->application_id,
            $participant->full_name,
            $participant->district,
        ];
    }
}
