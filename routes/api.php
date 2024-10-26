<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateApiKey;
use App\Http\Controllers\Api\ApplicantController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// New route for approved applicants
Route::middleware([ValidateApiKey::class, 'throttle:60,1'])->group(function () {
    Route::get('/approved-applicants', [ApplicantController::class, 'index']);
});
