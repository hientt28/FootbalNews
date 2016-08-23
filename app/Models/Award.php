<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = ['description'];

    public function bestPlayers()
    {
        return $this->hasMany(BestPlayerOfLeague::class);
    }
}
