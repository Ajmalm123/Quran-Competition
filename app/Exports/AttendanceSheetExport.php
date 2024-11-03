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
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceSheetExport implements FromCollection, WithMapping, WithHeadings, WithEvents
{
    use Exportable;

    public function __construct(public Collection $records)
    {
    }

    public function collection()
    {
        return $this->records;
    }

    public function map($application): array
    {
        static $slNo = 0;
        $slNo++;
        
        return [
            $slNo,
            $application->application_id,
            $application->full_name,
            Carbon::parse($application->date_of_birth)->format('d/m/Y'),
            $application->contact_number,
            '', // Place
            '', // Rep.Time
            '', // Signature
            '', // Token
        ];
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Application ID',
            'Full Name',
            'Date of Birth',
            'Phone',
            'Place',
            'Rep.Time',
            'Signature',
            'Token',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastColumn = 'I'; // Column I for Token
                $lastRow = $sheet->getHighestRow();

                // Set column widths
                $columnWidths = [
                    'A' => 8,  // Sl No
                    'B' => 15, // Application ID
                    'C' => 30, // Full Name
                    'D' => 15, // Date of Birth
                    'E' => 15, // Phone
                    'F' => 15, // Place
                    'G' => 12, // Rep.Time
                    'H' => 15, // Signature
                    'I' => 12, // Token
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
                $centerAlignColumns = ['A', 'B', 'D', 'E', 'F', 'G', 'H', 'I'];
                foreach ($centerAlignColumns as $col) {
                    $sheet->getStyle("{$col}2:{$col}{$lastRow}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Set row height
                $sheet->getRowDimension(1)->setRowHeight(30);
                foreach ($sheet->getRowIterator(2) as $row) {
                    $sheet->getRowDimension($row->getRowIndex())->setRowHeight(25);
                }
            },
        ];
    }
} 