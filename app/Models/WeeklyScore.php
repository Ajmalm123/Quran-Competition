<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_week_id',
        'score',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quizWeek(): BelongsTo
    {
        return $this->belongsTo(QuizWeek::class, 'quiz_week_id');
    }
}
