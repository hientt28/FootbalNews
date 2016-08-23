<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $dates = [
        'start',
        'end',
    ];

    protected $fillable = [
        'home_id',
        'guest_id',
        'league_season_id',
        'result',
        'rate',
        'location',
        'start',
        'end',
    ];
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function leagueSeason()
    {
        return $this->belongsTo(LeagueSeason::class);
    }
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
