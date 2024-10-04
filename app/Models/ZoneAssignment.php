<?php

namespace App\Models;

use App\Models\Zone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ZoneAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['zone_id', 'center_id', 'date', 'time','location'];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}