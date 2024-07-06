<?php

namespace App\Filament\Resources\ApplicationResource\Actions;

use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportPdfAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'export_pdf';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Export PDF')
            ->icon('heroicon-o-document-arrow-down')
            ->action(function (Model $record) {
                $pdf = PDF::loadView('pdf.application-pdf', ['record' => $record]);
            
                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->output();
                }, "{$record->application_id}.pdf");
            });
    }
}