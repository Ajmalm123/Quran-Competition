<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    use HasFactory;

    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ABANDONED = 'abandoned';

    protected $fillable = [
        'user_id',
        'quiz_week_id',
        'started_at',
        'submitted_at',
        'status',
        'weekly_score',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'weekly_score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quizWeek(): BelongsTo
    {
        return $this->belongsTo(QuizWeek::class, 'quiz_week_id');
    }

    public function attemptQuestions(): HasMany
    {
        return $this->hasMany(AttemptQuestion::class, 'attempt_id')->orderBy('display_order');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'attempt_questions', 'attempt_id', 'question_id')
            ->withPivot('display_order')
            ->orderByPivot('display_order');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'attempt_id');
    }

    public function isPerfectScore(): bool
    {
        return $this->weekly_score === 30 && $this->status === self::STATUS_COMPLETED;
    }
}
