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

Route::get('/thibyan', [WeeklyQuizController::class, 'index'])->name('quiz.index');
Route::get('/friday-quiz', [WeeklyQuizController::class, 'index'])->name('quiz.friday');
Route::get('/quiz', [WeeklyQuizController::class, 'index'])->name('quiz');

Route::prefix('quiz/api')->middleware('throttle:quiz-api')->group(function () {
    Route::get('/schedule', [WeeklyQuizController::class, 'schedule'])->name('quiz.api.schedule');
    Route::get('/leaderboard', [WeeklyQuizController::class, 'leaderboard'])->name('quiz.api.leaderboard');
    Route::get('/completed-questions', [WeeklyQuizController::class, 'completedQuestions'])->name('quiz.api.completed-questions');
    Route::post('/start', [WeeklyQuizController::class, 'startQuiz'])->name('quiz.api.start');
    Route::post('/submit', [WeeklyQuizController::class, 'submitQuiz'])->name('quiz.api.submit');
    Route::get('/result/{week}', [WeeklyQuizController::class, 'result'])->name('quiz.api.result');

    // Auth endpoints with intelligent quiz-auth protection
    Route::post('/auth/send-otp', [QuizAuthController::class, 'sendOtp'])->middleware('throttle:quiz-auth')->name('quiz.auth.send-otp');
    Route::post('/auth/verify-otp', [QuizAuthController::class, 'verifyOtp'])->middleware('throttle:quiz-auth')->name('quiz.auth.verify-otp');
    Route::post('/auth/register-password', [QuizAuthController::class, 'registerPassword'])->middleware('throttle:quiz-auth')->name('quiz.auth.register-password');
    Route::post('/auth/login-password', [QuizAuthController::class, 'loginPassword'])->middleware('throttle:quiz-auth')->name('quiz.auth.login-password');
    Route::post('/auth/forgot-password', [QuizAuthController::class, 'forgotPassword'])->middleware('throttle:quiz-auth')->name('quiz.auth.forgot-password');
    Route::post('/auth/reset-password', [QuizAuthController::class, 'resetPassword'])->middleware('throttle:quiz-auth')->name('quiz.auth.reset-password');
    Route::get('/auth/me', [QuizAuthController::class, 'me'])->name('quiz.auth.me');
    Route::post('/auth/logout', [QuizAuthController::class, 'logout'])->name('quiz.auth.logout');
});

// Direct Requirement REST endpoints
Route::prefix('auth')->middleware('throttle:quiz-auth')->group(function () {
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

Route::prefix('quiz')->middleware('throttle:quiz-api')->group(function () {
    Route::get('/weeks', [WeeklyQuizController::class, 'schedule']);
    Route::get('/current', [WeeklyQuizController::class, 'schedule']);
    Route::get('/completed-questions', [WeeklyQuizController::class, 'completedQuestions']);
    Route::post('/start', [WeeklyQuizController::class, 'startQuiz']);
    Route::post('/submit', [WeeklyQuizController::class, 'submitQuiz']);
    Route::get('/result/{week}', [WeeklyQuizController::class, 'result']);
});

Route::get('/leaderboard', [WeeklyQuizController::class, 'leaderboard'])->middleware('throttle:quiz-api');

use App\Http\Controllers\TibyanController;

// Route::get('/thibyan', [TibyanController::class, 'index'])->name('thibyan');
// Route::get('/tibyan', function () {
//     return redirect('/thibyan');
// })->name('tibyan');
// Route::get('/tibiyan', function () {
//     return redirect('/thibyan');
// });

// Route::post('/tibyan/api/demo-attempt', [TibyanController::class, 'recordDemoAttempt'])->name('tibyan.demo.attempt');
// Route::get('/tibyan/api/demo-stats', [TibyanController::class, 'getDemoStats'])->name('tibyan.demo.stats');
// Route::post('/thibyan/api/demo-attempt', [TibyanController::class, 'recordDemoAttempt'])->name('thibyan.demo.attempt');
// Route::get('/thibyan/api/demo-stats', [TibyanController::class, 'getDemoStats'])->name('thibyan.demo.stats');

// ==========================================
// Admin Live Lucky Draw Stage (Fullscreen Ceremony)
// ==========================================
use App\Http\Controllers\Admin\LuckyDrawStageController;

Route::middleware(['web', 'auth:admin'])->prefix('admin/lucky-draw')->group(function () {
    Route::get('/', [LuckyDrawStageController::class, 'index'])->name('admin.lucky-draw.stage');
    Route::get('/api/data', [LuckyDrawStageController::class, 'getData'])->name('admin.lucky-draw.api.data');
    Route::post('/api/publish', [LuckyDrawStageController::class, 'publishWinner'])->name('admin.lucky-draw.api.publish');
    Route::post('/api/redraw', [LuckyDrawStageController::class, 'redraw'])->name('admin.lucky-draw.api.redraw');
});

