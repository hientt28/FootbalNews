<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\UserMatch;
use App\Models\Match;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'avatar',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin()
    {
        if ($this->role == config('common.roles.admin')) {
            return true;
        } 

        return false;
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function socialNetworks()
    {
        return $this->hasMany(SocialNetwork::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function userMatch()
    {
        return $this->hasMany(UserMatch::class);
    }

    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }
}
