<?php

namespace App\Repositories\Position;

use App\Models\Position;
use App\Repositories\BaseRepository;

class PositionRepository extends BaseRepository
{
    public function __construct(Position  $position)
    {
        $this->model = $position;
    }

    public function lists($column, $key = null)
    {
        return $this->model->lists($column, $key);
    }
}