<?php

namespace App\Repositories\Team;

use App\Models\Team;
use App\Repositories\BaseRepository;

class TeamRepository extends BaseRepository
{
    public function __construct(Team  $team)
    {
        $this->model = $team;
    }

    public function lists($column, $key = null)
    {
        return $this->model->lists($column, $key);
    }
    
}