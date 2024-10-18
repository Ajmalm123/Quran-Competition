<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Application;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ApplicationExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithDrawings, WithEvents
{
    use Exportable;

    protected $imageHeight = 80;

    public function __construct(public Collection $records)
    {
    }

    public function collection()
    {
        return $this->records;
    }

    public function map($application): array
    {
        return [
            $application->application_id,
            '', // This cell will be used for the image
            $application->full_name,
            $application->date_of_birth,
            $application->district,
            $application->zone?->name,
            $application->zone?->assignment?->center_id ?? 'N/A',
            $application->zone?->assignment?->location ?? 'N/A',
            $application->zone?->assignment?->date ? \Carbon\Carbon::parse($application->zone?->assignment?->date)->format('F j, Y') : 'N/A',
            $application->zone?->assignment?->time ? \Carbon\Carbon::parse($application->zone?->assignment?->time)->format('h:i A') : 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            'Application ID',
            'Photo',
            'Name',
            'Date of Birth',
            'District',
            'Zone',
            'Center Name',
            'Center Location',
            'Center Date',
            'Center Time',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_DATE_TIME3,
        ];
    }

    public function drawings()
    {
        $drawings = [];
        foreach ($this->records as $index => $application) {
            // $imagePath = storage_path("app/applications/{$application->application_id}.jpg");
            $imagePath = public_path("storage/{$application->passport_size_photo}");
            if (file_exists($imagePath)) {
                $drawing = new Drawing();
                $drawing->setName("Profile Picture");
                $drawing->setDescription("Profile Picture");
                $drawing->setPath($imagePath);
                $drawing->setHeight($this->imageHeight);
                $drawing->setWidth($this->imageHeight); // Make it square
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawing->setCoordinates("B" . ($index + 2));
                $drawings[] = $drawing;
            }
        }
        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastColumn = $sheet->getHighestColumn();
                $lastRow = $sheet->getHighestRow();

                // Set column widths
                $columnWidths = [
                    'A' => 15, 'B' => 15, 'C' => 25, 'D' => 15, 'E' => 15, 'F' => 15,
                    'G' => 25, 'H' => 25, 'I' => 15, 'J' => 15
                ];

                foreach ($columnWidths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                // Style the header row
                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4472C4']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Style the data rows
                $dataRange = "A2:{$lastColumn}{$lastRow}";
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // Center-align specific columns
                $centerAlignColumns = ['A', 'B', 'D', 'E', 'F', 'G', 'H', 'J'];
                foreach ($centerAlignColumns as $col) {
                    $sheet->getStyle("{$col}2:{$col}{$lastRow}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Wrap text for all columns
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->getAlignment()->setWrapText(true);

                // Set row height for all rows
                $rowHeight = $this->imageHeight + 10; // Add some padding
                foreach ($sheet->getRowIterator(2) as $row) {
                    $sheet->getRowDimension($row->getRowIndex())->setRowHeight($rowHeight);
                }
            },
        ];
    }
}
