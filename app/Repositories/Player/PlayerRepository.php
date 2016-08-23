<?php

namespace App\Repositories\Player;

use App\Models\Player;
use App\Repositories\BaseRepository;

class PlayerRepository extends BaseRepository
{
    public function __construct(Player  $player)
    {
        $this->model = $player;
    }
}