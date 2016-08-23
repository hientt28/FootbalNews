<?php
namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Match\MatchRepository;
use LRedis;

class MatchController extends Controller
{
    protected $matchRepository;

    public function __construct(MatchRepository $matchRepository) 
    {
        $this->matchRepository = $matchRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $matches = [];
        $datafields = [
            'id' => 'Id', 
            'home_id' => 'home team', 
            'guest_id' => 'guest team', 
            'league_season_id' => 'season league', 
            'result' => 'result', 
            'rate' => 'rate',
            'location' => 'location',
            'start' => 'start',
            'end' => 'end',
        ];

       /* $this->matchRepository->create([
            'home_id' => rand(), 
            'guest_id' => rand(), 
            'league_season_id' => rand(), 
            'result' => '4-3', 
            'rate' => rand(0, 10) / 10,
            'location' => 'Old transford',
            'start' => \Carbon\Carbon::now(),
        ]);*/

        if(app('request')->ajax()) {
            $matches = $this->matchRepository->all();
            $teams = $this->matchRepository->team->all();
            $flag = $request->input('sendNotification');
            $resultBet = true;
            if ($flag) {
                $dataBet = $request->all();
                $user = auth()->user();
                $resultBet = $this->matchRepository->createOrUpdateBet($dataBet);
                if ($resultBet) {
                    $redis = LRedis::connection();
                    $data = app()->make('stdClass');
                    $data->user_id = $user->id;
                    $data->user_name = $user->name;
                    $data->avatar = $user->avatar;
                    $redis->publish('message', json_encode($data));
                }
            } else {
                $resultBet = false;
            }

            return response()->json([
                'datafields' => $datafields,
                'records' => $matches,
                'teams' => $teams,
                'status' => 'OK',  
                'resultBet' => $resultBet, 
            ]);
        }

        return view('layouts.matches');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (app('request')->ajax()) {           
            
        
            return view('admin.match.index');
        }

        return view('admin.match.create');       
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}