<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TibyanDemoAttempt extends Model
{
    use HasFactory;

    protected $table = 'tibyan_demo_quiz_attempts';

    protected $fillable = [
        'visitor_id',
        'session_id',
        'selected_option',
        'is_correct',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];
}
