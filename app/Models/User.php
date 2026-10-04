<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'locality',
        'territory',
        'country_code',
        'whatsapp_number',
        'whatsapp_verified',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'whatsapp_verified' => 'boolean',
            'age' => 'integer',
        ];
    }

    public function quizAttempts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'user_id');
    }

    public function weeklyScores(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WeeklyScore::class, 'user_id');
    }

    public function luckyDrawWins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LuckyDraw::class, 'winner_user_id');
    }

    public function getTotalPointsAttribute(): int
    {
        return (int) $this->weeklyScores()->sum('score');
    }

    public function isAccountActive(): bool
    {
        return $this->status === 'active';
    }
}
