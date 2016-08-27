<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestPlayerOfLeague extends Model
{
    protected $fillable = [
        'award_id',
        'player_id',
        'league_season_id',
    ];

    public function award()
    {
        return $this->belongsTo(Award::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }


    public function leagueSeason()
    {
        return $this->belongsTo(LeagueSeason::class);
    }
}
