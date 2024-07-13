<?php

namespace App\Models;

use Filament\Panel;
use App\Models\ZoneAssignment;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Zone extends Authenticatable implements FilamentUser
{
    use HasFactory;

    protected $fillable = ['name','email','password'];

    public function assignments()
    {
        return $this->hasMany(ZoneAssignment::class);
    }
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    const AREA = [
        'Native' => 'Native',
        'Abroad' => 'Abroad',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@test.com');
    }
}