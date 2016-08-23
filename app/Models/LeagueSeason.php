<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeagueSeason extends Model
{
    protected $table = 'league_season';
    
    protected $fillable = [
        'league_id',
        'season_id',
    ];
    
    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }
}
