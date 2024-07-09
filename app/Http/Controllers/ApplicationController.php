<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmailJob;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Requests\ApplicationRequest;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function store(ApplicationRequest $request)
    {
        // dd($request);
        try {
            $validatedData = $request->validated();
            // Handle the file uploads
            if ($request->hasFile('passport_size_photo')) {
                $passportPhotoPath = $request->file('passport_size_photo')->store('passport_photos', 'public');
                $validatedData['passport_size_photo'] = $passportPhotoPath;
            }
            if ($request->hasFile('birth_certificate')) {
                $birthCertificatePath = $request->file('birth_certificate')->store('birth_certificates', 'public');
                $validatedData['birth_certificate'] = $birthCertificatePath;
            }
            if ($request->hasFile('letter_of_recommendation')) {
                $recommendationPath = $request->file('letter_of_recommendation')->store('letter_of_recommendation', 'public');
                $validatedData['letter_of_recommendation'] = $recommendationPath;
            }

            $application = new Application($validatedData);
            $application->save();

            $dispatchData = [
                'page' => 'emails.application-recieved',
                'application' => $application,
                'subject' => 'Application Received',
                'message' => 'Thank you for your application. We have received it and will review it shortly.',
            ];

            SendEmailJob::dispatch($dispatchData);

            return response()->json([
                'success' => true,
                'message' => 'Application created successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error("Error creating application: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your application. Please try again later.'
            ], 500);
        }
    }
}
