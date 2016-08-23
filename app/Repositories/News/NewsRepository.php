<?php

namespace App\Repositories\News;

use App\Repositories\BaseRepository;
use App\Models\Match;
use App\Models\Team;
use App\Models\Country;
use App\Models\Continent;
use App\Models\League;
use App\Models\Event;
use App\Models\Notification;
use DB;

class NewsRepository extends BaseRepository
{

	public $team;
	public $country;
	public $continent;
	public $leagues;
    public $notification;

    public function __construct(Match $match, Team $team, Country $country, Continent $continent, League $leagues, Event $event, Notification $notification) {
    	$this->model = $match;
    	$this->team = $team;
    	$this->country = $country;
    	$this->continent = $continent;
    	$this->leagues = $leagues;
    	$this->event = $event;
        $this->notification = $notification;
    }
}
