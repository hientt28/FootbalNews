<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMatch extends Model
{
    protected $fillable = [
        'user_id',
        'match_id',
        'team_guess',
        'result',
        'price',
    ];
    
    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
