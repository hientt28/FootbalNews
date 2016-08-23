<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'country_id',
        'description',
    ];

    public function leagueSeasons()
    {
        return $this->hasMany(LeagueSeason::class);
    }

    public function seasons()
    {
        return $this->belongsToMany(Season::class);
    }
}
