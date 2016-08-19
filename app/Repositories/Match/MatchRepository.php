<?php

namespace App\Repositories\Match;

use App\Repositories\BaseRepository;
use App\Models\Match;
use App\Models\Team;
use App\Models\Country;
use App\Models\Continent;
use App\Models\League;
use App\Models\Event;
use DB;

class MatchRepository extends BaseRepository
{

	public $team;
	public $country;
	public $continent;
	public $leagues;

    public function __construct(Match $match, Team $team, Country $country, Continent $continent, League $leagues, Event $event) {
    	$this->model = $match;
    	$this->team = $team;
    	$this->country = $country;
    	$this->continent = $continent;
    	$this->leagues = $leagues;
    	$this->event = $event;
    }

    public function updateMatch($data, $id)
    {
        DB::transaction(function () use ($data, $id){
            $events_data = $data['events_data'];
            $match = $this->find($id);
            if ($match) {
                $events = $match->events();
                if ($events && $events_data) {
                    $events->delete();
                    $events_data = json_decode($events_data);
                    $eventsArr = [];
                    foreach ($events_data as $key => $value) {
                        foreach ($value as $k => $v) {
                            if ($k == 'content' || $k == 'time') {
                                $eventsArr[$k] = $v;
                            }
                        }
                    }
                }
                $events->sameMany(new $this->event($eventsArr));
                $match->update($data);
            }        
        });
    }
}
