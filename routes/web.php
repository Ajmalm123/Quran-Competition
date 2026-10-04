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
    $categories = \App\Models\Category::where('is_active', true)->orderBy('id', 'desc')->get();
    return view('welcome-old', compact('abroadZones', 'nativeZones', 'categories'));
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

Route::get('/results', function () {
    return view('result-check');
});

Route::post('/results', function () {
    return response()->json([]);
})->name('screening.result.check');

Route::get('/resultsshow', function () {
    return view('result-show');
});


Route::post('/log-ajax-error', [ApplicationController::class, 'logAjaxError'])->name('log.ajax.error');


Route::post('/apply/application', [ApplicationController::class, 'store'])->name('application.store');

Route::get('/api/categories/{id}', function ($id) {
    $category = \App\Models\Category::find($id);
    return response()->json($category);
})->name('api.categories.show');

// ==========================================
// Friday Night Quiz - Public Web & SPA API
// ==========================================
use App\Http\Controllers\WeeklyQuizController;
use App\Http\Controllers\QuizAuthController;

Route::get('/quiz', [WeeklyQuizController::class, 'index'])->name('quiz.index');
Route::get('/friday-quiz', [WeeklyQuizController::class, 'index'])->name('quiz.friday');

Route::prefix('quiz/api')->group(function () {
    Route::get('/schedule', [WeeklyQuizController::class, 'schedule'])->name('quiz.api.schedule');
    Route::get('/leaderboard', [WeeklyQuizController::class, 'leaderboard'])->name('quiz.api.leaderboard');
    Route::post('/start', [WeeklyQuizController::class, 'startQuiz'])->name('quiz.api.start');
    Route::post('/submit', [WeeklyQuizController::class, 'submitQuiz'])->name('quiz.api.submit');
    Route::get('/result/{week}', [WeeklyQuizController::class, 'result'])->name('quiz.api.result');

    // Auth endpoints
    Route::post('/auth/send-otp', [QuizAuthController::class, 'sendOtp'])->name('quiz.auth.send-otp');
    Route::post('/auth/verify-otp', [QuizAuthController::class, 'verifyOtp'])->name('quiz.auth.verify-otp');
    Route::post('/auth/register-password', [QuizAuthController::class, 'registerPassword'])->name('quiz.auth.register-password');
    Route::post('/auth/login-password', [QuizAuthController::class, 'loginPassword'])->name('quiz.auth.login-password');
    Route::post('/auth/forgot-password', [QuizAuthController::class, 'forgotPassword'])->name('quiz.auth.forgot-password');
    Route::post('/auth/reset-password', [QuizAuthController::class, 'resetPassword'])->name('quiz.auth.reset-password');
    Route::get('/auth/me', [QuizAuthController::class, 'me'])->name('quiz.auth.me');
    Route::post('/auth/logout', [QuizAuthController::class, 'logout'])->name('quiz.auth.logout');
});

// Direct Requirement REST endpoints
Route::prefix('auth')->group(function () {
    Route::post('/send-otp', [QuizAuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [QuizAuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [QuizAuthController::class, 'sendOtp']);
    Route::post('/register-password', [QuizAuthController::class, 'registerPassword']);
    Route::post('/login-password', [QuizAuthController::class, 'loginPassword']);
    Route::post('/forgot-password', [QuizAuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [QuizAuthController::class, 'resetPassword']);
    Route::get('/me', [QuizAuthController::class, 'me']);
    Route::post('/logout', [QuizAuthController::class, 'logout']);
});

Route::prefix('quiz')->group(function () {
    Route::get('/weeks', [WeeklyQuizController::class, 'schedule']);
    Route::get('/current', [WeeklyQuizController::class, 'schedule']);
    Route::post('/start', [WeeklyQuizController::class, 'startQuiz']);
    Route::post('/submit', [WeeklyQuizController::class, 'submitQuiz']);
    Route::get('/result/{week}', [WeeklyQuizController::class, 'result']);
});

Route::get('/leaderboard', [WeeklyQuizController::class, 'leaderboard']);

