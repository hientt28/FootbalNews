<?php

namespace App\Repositories\Match;

use App\Repositories\BaseRepository;
use App\Models\Match;
use App\Models\Team;
use App\Models\Country;
use App\Models\Continent;
use App\Models\League;
use App\Models\Event;
use App\Models\Notification;
use LRedis;
use DB;

class MatchRepository extends BaseRepository
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

    public function updateMatch($data, $id)
    {
        DB::transaction(function () use ($data, $id) {
            $events_data = $data['events_data'];
            $match = $this->find($id);
            if ($match) {
                $events = $match->events();
                if ($events && $events_data) {
                    $events->delete();
                    $events_data = json_decode($events_data);
                    $eventsArr = collect([]);
                    foreach ($events_data as $key => $value) {
                        $temp = [];
                        if($value->content != null && $value->content != "") {
                            $temp['content']  = $value->content;
                        }
                        if($value->time != null && $value->time != "") {
                            $temp['time']  = $value->time;
                        }
                        $eventsArr->push($temp);
                    }
                    if(!$eventsArr->isEmpty()) {
                         $match->events()->create($eventsArr->all());
                    }
                }

                if($data['home_goal'] && $data['guest_goal']) {
                     $data['result']  = $data['home_goal'] . ' - ' . $data['guest_goal'];
                }

                $match->update($data);
            }        
        });

        $redis = LRedis::connection();
        $redis->publish('message', 'update match from admin!!');
    }

    public function createOrUpdateBet($dataBet)
    {
        $flag = true;
        DB::transaction(function () use ($dataBet){
            try {
                if (!$dataBet) {
                    return false;
                }
                $user = auth()->user();   
                
                $teamGuess = $dataBet['teamGuess'];
                $result = $dataBet['result'];
                $price = $dataBet['price'];
                $matchId = $dataBet['matchId'];
                $userbet = $user->userMatch()->where([
                    'match_id' => $matchId
                ])->first(); 
                if ($userbet != null) {
                    $userbet->update([
                        'team_guess' => $teamGuess,
                        'result' => $result,
                        'price' => $price,
                    ]);
                } else {
                    $user->userMatch()->create([
                        'match_id' => $matchId,
                        'team_guess' => $teamGuess,
                        'result' => $result,
                        'price' => $price,                   
                    ]);
                }     
                $match = $this->model->find($matchId);
                $home = $this->team->find($match->home_id);
                $guest = $this->team->find($match->guest_id);

               $this->createNotification($user->id, '0', $matchId, Match::class, 'User ' . $user->name . 'has bet a match' . $home->name . ' - '.$guest->name . ' [' . $matchId . ']');
            } catch (Exception $e) {
                $flag = false;
            }
        });

        return $flag;
    }

    public function createNotification($userId, $status, $target_id, $target_class, $message)
    {
        return $this->notification->create([
            'user_id' => $userId,
            'status' => $status,
            'target_id' => $target_id,
            'target_class' => $target_class,
            'message' => $message,
        ]);
    }
}
