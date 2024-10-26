<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateApiKey;
use App\Http\Controllers\Api\ApplicantController;
use App\Http\Controllers\Api\ApplicationController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([ValidateApiKey::class, 'throttle:60,1'])->group(function () {
    Route::get('/approved-applicants', [ApplicantController::class, 'index']);
    // Update the endpoint to application/marks/update
    Route::post('/application/marks/update', [ApplicationController::class, 'updateMarks']);
});
