<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect('/apply');
// });

Route::get('/', function () {
    return view('blank');
});

Route::get('/apply', function () {
    return view('welcome');
})->name('apply');

Route::get('/application', function () {
    return view('emails.application-recieved');
});
Route::get('/approved', function () {
    return view('emails.application-approved');
});

Route::get('/application-status', function () {
    return view('application-status');
});

Route::get('/application-approved-pdf', function () {
    return view('pdf.application-approved.blade');
});

Route::get('/application-profile-status', function () {
    return view('application-profile-status');
});




Route::post('/apply/application', [ApplicationController::class, 'store'])->name('application.store');
