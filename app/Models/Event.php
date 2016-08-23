<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $dates = ['time'];

    protected $fillable = [
        'match_id',
        'content',
        'time',
    ];

    public function match()
    {
        return $this->belongsTo(Match::class);
    }
}
