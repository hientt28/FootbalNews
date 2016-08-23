<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'country_id',
        'description',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    public function leaguaSeasonTeams()
    {
        return $this->hasMany(LeagueSeasonTeam::class);
    }
}
