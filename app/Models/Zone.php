<?php

namespace App\Models;

use App\Models\ZoneAssignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function assignments()
    {
        return $this->hasMany(ZoneAssignment::class);
    }
}