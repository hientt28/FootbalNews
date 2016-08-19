<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeagueSeasonTeam extends Model
{
    protected $fillable = [
        'league_season_id',
        'team_id',
        'score',
    ];
    
    public function leagueSeason()
    {
        return $this->belongsTo(LeagueSeason::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
