<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'gender_restriction', // 'Male', 'Female', 'Both'
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    const GENDER_RESTRICTIONS = [
        'Male' => 'Male',
        'Female' => 'Female',
        'Both' => 'Both'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function isGenderAllowed($gender)
    {
        return $this->gender_restriction === 'Both' || $this->gender_restriction === $gender;
    }
} 