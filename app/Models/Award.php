<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = ['description'];

    public function bestPlayers()
    {
        return $this->hasMany(BestPlayerOfLeague::class);
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
