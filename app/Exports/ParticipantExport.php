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

class ParticipantExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, WithEvents
{
    use Exportable;

    public function __construct(public Collection $records)
    {
    }

    public function collection()
    {
        return $this->records->sortBy('position');
    }

    public function map($application): array
    {
        return [
            $application->position ?? 'N/A',
            $application->application_id,
            $application->full_name,
            $application->token_number ?? 'N/A',
            $application->marks ?? 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            'Position',
            'Application ID',
            'Full Name',
            'Token Number',
            'Mark',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_00, // Format marks with 2 decimal places
        ];
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
                    'A' => 10, // Position
                    'B' => 15, // Application ID
                    'C' => 30, // Full Name
                    'D' => 15, // Token Number
                    'E' => 10, // Mark
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

                // Center-align all columns except Full Name
                $centerAlignColumns = ['A', 'B', 'D', 'E'];
                foreach ($centerAlignColumns as $col) {
                    $sheet->getStyle("{$col}2:{$col}{$lastRow}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Wrap text for all columns
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->getAlignment()->setWrapText(true);

                // Set a fixed row height for all rows
                $rowHeight = 25;
                foreach ($sheet->getRowIterator(2) as $row) {
                    $sheet->getRowDimension($row->getRowIndex())->setRowHeight($rowHeight);
                }
            },
        ];
    }
} 