<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function updateMarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'applicationId' => 'required|string',
            'marks' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $application = Application::where('application_id', $request->applicationId)->first();

        if (!$application) {
            return response()->json(['status' => 'error', 'message' => 'Application not found'], 404);
        }

        $application->marks = $request->marks;
        $application->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Marks updated successfully',
            'application' => [
                'application_id' => $application->application_id,
                'marks' => $application->marks,
            ]
        ]);
    }
}
