<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'news';
    
    protected $fillable = [
        'user_id',
        'match_id',
        'title',
        'content',
        'image',
    ];
    
    public function comments()
    {
        return $this->hasMany(Comment::class, 'new_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function match()
    {
        return $this->belongsTo(Match::class);
    }
}
