<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $dates = ['birthday'];
    
    protected $fillable = [
        'name',
        'birthday',
        'country_id',
        'position_id',
        'team_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function bestPlayerOfLeagues()
    {
        return $this->hasMany(BestPlayerOfLeague::class);
    }
}
