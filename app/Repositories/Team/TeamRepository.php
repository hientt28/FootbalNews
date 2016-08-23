<?php

namespace App\Repositories\Team;

use App\Models\Team;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

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

    public function groupByDate()
     {
         $team = $this->model->all()
                 ->groupBy(function($date) {
                       return Carbon::parse($date->created_at)->format('Y-m-d');
                 });

         return $team;
     }
}