<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_week_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function quizWeek(): BelongsTo
    {
        return $this->belongsTo(QuizWeek::class, 'quiz_week_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function getCorrectOptionTextAttribute(): ?string
    {
        return match ($this->correct_option) {
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
            default => null,
        };
    }
}
