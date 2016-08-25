<?php

namespace App\Repositories\News;

use App\Repositories\BaseRepository;
use App\Models\News;
use App\Models\Match;
use App\Models\Team;
use DB;

class NewsRepository extends BaseRepository
{
    public $match;
    public $team;

    public function __construct(News $news,Match $match,Team $team) {
    	$this->model = $news;
        $this->match = $match;
        $this->team = $team;
    }
}
