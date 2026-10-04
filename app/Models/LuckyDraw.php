<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LuckyDraw extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_DRAWN = 'drawn';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'quiz_week_id',
        'winner_user_id',
        'status',
        'drawn_at',
        'confirmed_at',
        'published_at',
        'notes',
    ];

    protected $casts = [
        'drawn_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function quizWeek(): BelongsTo
    {
        return $this->belongsTo(QuizWeek::class, 'quiz_week_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    public function isLocked(): bool
    {
        return in_array($this->status, [self::STATUS_CONFIRMED, self::STATUS_PUBLISHED]);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }
}
