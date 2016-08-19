<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $dates = [
        'start',
        'end',
    ];
    
    protected $fillable = [
        'start',
        'end',
    ];

    public function leagueSeasons()
    {
        return $this->hasMany(LeagueSeason::class);
    }
}
