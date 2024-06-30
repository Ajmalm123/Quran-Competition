<?php

namespace App\Jobs;

use App\Mail\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
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
            Mail::to($this->data['application']->email)->send(new Application([
                'application' => $this->data['application'],
                'subject' => $this->data['subject'],
                'message' => $this->data['message'],
            ]));

            // Log success
            \Illuminate\Support\Facades\Log::info("Email sent successfully to {$this->data['application']->email}");
        } catch (\Exception $e) {
            // Log failure
            \Illuminate\Support\Facades\Log::error("Failed to send email to {$this->data['application']->email}: ", ['exception' => $e]);
        }
    }
}
