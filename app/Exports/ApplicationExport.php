<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Application;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;



class ApplicationExport implements FromCollection, WithMapping, WithHeadings,WithColumnFormatting
{
    use Exportable;

    public function __construct(public Collection $records)
    {

    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->records;
    }


    /**
     * @param Application $application
     */
    public function map($application): array
    {
        return [
            $application->application_id,
            $application->full_name,
            $application->gender,
            Carbon::parse($application->date_of_birth)->age,
            $application->mother_tongue,
            $application->educational_qualification,
            $application->aadhar_number,
            $application->job,
            $application->contact_number,
            $application->whatsapp,
            $application->email,
            $application->c_address,
            $application->pr_address,
            $application->district,
            $application->pincode,
            $application->institution_name,
            $application->is_completed_ijazah,
            $application->qirath_with_ijazah,
            $application->primary_competition_participation,
            $application->zone,
            $application->status,
            dateFormat($application->created_at),
            dateFormat($application->updated_at),
        ];
    }   

    public function headings(): array
    {
        return [
            'Application ID',
            'Full Name',
            'Gender',
            'Age',
            'Mother Tongue',
            'Educational Qualification',
            'Aadhar Number',
            'Job',
            'Contact Number',
            'WhatsApp',
            'Email',
            'Current Address',
            'Permanent Address',
            'District',
            'Pincode',
            'Institution Name',
            'Completed Ijazah',
            'Qirath with Ijazah',
            'Primary Competition Participation',
            'Zone',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER,
            'I'=> NumberFormat::FORMAT_NUMBER,
        ];
    }
}
