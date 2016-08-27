<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function getUpdatedAtStatusAttribute()
    {
        $now = Carbon::now();
        $status = $this->updated_at->diffForHumans($now);

        return $status;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['updated_at_status'] = $this->updated_at_status;
        return $array;
    }
}
