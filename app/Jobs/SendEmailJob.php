<?php

namespace App\Jobs;

use App\Mail\Application;
use Illuminate\Bus\Queueable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Generate PDF
            $pdf = Pdf::loadView('pdf.application-approved', ['application' => $this->data['application']]);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'DejaVu Sans',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'debugCss' => true,
            ]);
            // Create folder if it doesn't exist
            $folderPath = 'Application Approved Pdfs';
            Storage::disk('local')->makeDirectory($folderPath);

            $pdfFileName = 'application_' . $this->data['application']->application_id . '_approved.pdf';
            $pdfFullPath = $folderPath . '/' . $pdfFileName;

            // Save PDF to the specified folder
            Storage::disk('local')->put($pdfFullPath, $pdf->output());

            $pdfPath = Storage::disk('local')->path($pdfFullPath);

            Mail::to($this->data['application']->email)->send(new Application([
                'page' => $this->data['page'],
                'application' => $this->data['application'],
                'subject' => $this->data['subject'],
                'message' => $this->data['message'],
                'pdfPath' => $pdfPath
            ]));
            // Log success
            \Illuminate\Support\Facades\Log::info("Email sent successfully to {$this->data['application']->email}");
        } catch (\Exception $e) {
            // Log failure
            \Illuminate\Support\Facades\Log::error("Failed to send email to {$this->data['application']->email}: ", ['exception' => $e]);
        }
    }
}
