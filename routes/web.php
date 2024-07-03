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

Route::post('/apply/application', [ApplicationController::class, 'store'])->name('application.store');
