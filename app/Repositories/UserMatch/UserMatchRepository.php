<?php

namespace App\Repositories\UserMatch;

use App\Models\UserMatch;
use App\Repositories\BaseRepository;

class UserMatchRepository extends BaseRepository
{
    public function __construct(UserMatch  $userMatch)
    {
        $this->model = $userMatch;
    }

    public function chart()
    {
        $userMatch = $this->model->get()->groupBy('user_id');
        
        return $userMatch;
    }
}