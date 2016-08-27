<?php

namespace App\Models;

use Carbon\Carbon;
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
