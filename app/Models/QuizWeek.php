<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuizWeek extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_UPCOMING = 'upcoming';
    public const STATUS_LIVE = 'live';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'week_number',
        'quiz_date',
        'start_at',
        'end_at',
        'status',
    ];

    protected $casts = [
        'week_number' => 'integer',
        'quiz_date' => 'date',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /**
     * Questions belonging to this week (configured to exactly 6 questions)
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'quiz_week_id')->orderBy('sort_order');
    }

    /**
     * Quiz attempts for this week
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_week_id');
    }

    /**
     * Weekly scores recorded for this week
     */
    public function weeklyScores(): HasMany
    {
        return $this->hasMany(WeeklyScore::class, 'quiz_week_id');
    }

    /**
     * Lucky draw record for this week
     */
    public function luckyDraw(): HasOne
    {
        return $this->hasOne(LuckyDraw::class, 'quiz_week_id');
    }

    /**
     * Automatic/computed status based on current time
     */
    public function getComputedStatusAttribute(): string
    {
        if (in_array($this->status, [self::STATUS_DRAFT, self::STATUS_CANCELLED])) {
            return $this->status;
        }

        $now = Carbon::now();

        if ($now->lt($this->start_at)) {
            return self::STATUS_UPCOMING;
        }

        if ($now->between($this->start_at, $this->end_at)) {
            return self::STATUS_LIVE;
        }

        return self::STATUS_COMPLETED;
    }

    /**
     * Check if exactly 6 valid questions are configured
     */
    public function canPublish(): bool
    {
        if ($this->questions()->count() !== 6) {
            return false;
        }

        foreach ($this->questions as $question) {
            if (
                empty($question->question_text) ||
                empty($question->option_a) ||
                empty($question->option_b) ||
                empty($question->option_c) ||
                empty($question->option_d) ||
                !in_array($question->correct_option, ['A', 'B', 'C', 'D'])
            ) {
                return false;
            }
        }

        return true;
    }

    public function isLive(): bool
    {
        return $this->computed_status === self::STATUS_LIVE;
    }

    public function isCompleted(): bool
    {
        return $this->computed_status === self::STATUS_COMPLETED;
    }
}
