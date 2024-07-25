<?php

use App\Models\Zone;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

// Route::get('/', function () {
//     return redirect('/apply');
// });

Route::get('/', function () {
    return view('blank');
});

Route::get('/', function () {
    return redirect('https://event.aslamquranaward.com/');
});

Route::get('/apply', function () {
    $abroadZones = Zone::where('area', 'Abroad')->select('id', 'name')->get();
    $nativeZones = Zone::where('area', 'Native')->select('id', 'name')->get();
    return view('welcome', compact('abroadZones', 'nativeZones'));
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
    return view('pdf.application-approved');
});

Route::get('/application-profile-status', function () {
    return view('application-profile-status');
});




Route::post('/apply/application', [ApplicationController::class, 'store'])->name('application.store');
