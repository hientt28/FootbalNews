<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Match;
use App\Models\Team;
use App\Models\Country;
use App\Models\News;
use App\Models\League;
use App\Models\Event;
use App\Models\Notification;
use DB;

class NewsRepositories extends BaseRepository
{

	public $team;
	public $country;
	public $continent;
	public $leagues;
    public $notification;

    public function __construct(Match $match, Team $team, Country $country, News $news, League $leagues, Event $event, Notification $notification) {
    	$this->model = $news;
    	$this->team = $team;
    	$this->country = $country;
    	$this->continent = $continent;
    	$this->leagues = $leagues;
    	$this->event = $event;
        $this->notification = $notification;
    }
}
