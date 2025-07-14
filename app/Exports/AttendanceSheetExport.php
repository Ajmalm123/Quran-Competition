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
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

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
            $application->category?->name ?? 'N/A',
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
            'Category',
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
                $lastColumn = 'I';
                $lastRow = $sheet->getHighestRow();

                // Set page setup for A4 portrait
                $sheet->getPageSetup()
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0)
                    ->setHorizontalCentered(true)
                    ->setPrintArea("A1:{$lastColumn}{$lastRow}")
                    ->setRowsToRepeatAtTopByStartAndEnd(1, 1); // Repeat header row on each page

                // Set margins (in inches)
                $sheet->getPageMargins()
                    ->setTop(0.5)
                    ->setRight(0.5)
                    ->setBottom(0.5)
                    ->setLeft(0.5)
                    ->setHeader(0.3)
                    ->setFooter(0.3);

                // Optimize column widths for A4 portrait (in points)
                $columnWidths = [
                    'A' => 6,  // Sl No
                    'B' => 13, // Application ID
                    'C' => 25, // Full Name
                    'D' => 12, // Category
                    'E' => 12, // Date of Birth
                    'F' => 12, // Phone
                    'G' => 12, // Place
                    'H' => 10, // Rep.Time
                    'I' => 12, // Signature
                    'J' => 10, // Token
                ];

                foreach ($columnWidths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                // Style the header row
                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => [
                        'bold' => true, 
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID, 
                        'color' => ['rgb' => '4472C4']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER, 
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                ]);

                // Style the data rows
                $dataRange = "A2:{$lastColumn}{$lastRow}";
                $sheet->getStyle($dataRange)->applyFromArray([
                    'font' => [
                        'size' => 10
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN
                        ]
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                ]);

                // Center-align specific columns
                $centerAlignColumns = ['A', 'B', 'D', 'E', 'F', 'G', 'H', 'I'];
                foreach ($centerAlignColumns as $col) {
                    $sheet->getStyle("{$col}2:{$col}{$lastRow}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Optimize row heights
                $sheet->getRowDimension(1)->setRowHeight(20);
                foreach ($sheet->getRowIterator(2) as $row) {
                    $sheet->getRowDimension($row->getRowIndex())->setRowHeight(18);
                }

                // Add print gridlines
                $sheet->setShowGridlines(true);
                $sheet->setPrintGridlines(true);
            },
        ];
    }
} 