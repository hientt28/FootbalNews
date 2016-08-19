<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Match\MatchRepository;
use DB;

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
    public function index()
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
            return response()->json([
                'datafields' => $datafields,
                'records' => $matches,
                200,
                'status' => 'OK',   
            ]);
        }

        return view('admin.match.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*try {
            DB::beginTransaction();
            $continent = \App\Models\Continent::create([
                'name' => 'EU',
            ]);

            $country = \App\Models\Country::create([
                'name' => 'England',
                'continent_id' => $continent->id
            ]);

            \App\Models\Team::create([
                'name' => 'Manchester United',
                'logo' => asset('images/MU.png'),
                'country_id' => $country->id,
                'description' => 'MU team',     
            ]);

            \App\Models\Team::create([
                'name' => 'Chelsea',
                'logo' => asset('images/CS.jpg'),
                'country_id' => $country->id,
                'description' => 'Chelsea team',     
            ]);

            $season = \App\Models\Season::create([
                'start' => \Carbon\Carbon::now(),
                'end' => \Carbon\Carbon::now()
            ]);

            $league = \App\Models\League::create([
                'name' => 'Champion Leagues',
                'logo' => asset('images/champions_league.png'),
                'country_id' => 1,
                'description' => 'Champions Leagues',     
            ]);

            $league->seasons()->sync([$season->id]);

        } catch(Exception $e){
            DB::rollBack();
            throw new Exception($e->getMessage());          
        }
        DB::commit();*/
        if(app('request')->ajax()) {
            return $this->getDataAjax();
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
        $message = trans('message.match.create');
        $data = $request->all();

        try {
            $this->matchRepository->create($data);
        } catch (Exception $e) {
            $message = $e->getMessage();

            return view('admin.match.index')->withError($message);
        } finally {
            
        }

        return view('admin.match.index')->withSuccess($message);
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
        if (app('request')->ajax()) {
            $events = $this->matchRepository->event->whereMatchId($id)->get();
            $data = $this->getDataAjax();
            if ($events != null) {
                $data['datafields_events'] = [
                    'id' => 'Id',
                    'content' => 'Content',
                    'time' => 'Time',
                ];
                $data['events'] = $events;
            }
            
            return response()->json($data);
        }

        $match = $this->matchRepository->find($id);
        $home = $this->matchRepository->team->find($match->home_id);
        $guest = $this->matchRepository->team->find($match->guest_id);
        return view('admin.match.edit')->with(
            [
                'match' => $match, 
                'home' => $home, 
                'guest' => $guest,
            ]);
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
        $message = trans('message.match.update');
        $data = $request->all();
        try { 
            $this->matchRepository->updateMatch($data, $id);
               
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        return back()->withSuccess($message);
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

    public function getDataAjax()
    {
        
        $datafields = [
            'id' => 'Id', 
            'name' => 'Name', 
            'logo' => 'logo', 
            'country_id' => 'Country', 
            'description' => 'Description', 
        ];
        $teams = $this->matchRepository->team->all();
        $leagues = $this->matchRepository->leagues->all();

        return [
            'datafields' => $datafields,
            'records' => $teams,
            'leagues' => $leagues,
            200,
            'status' => 'OK',   
        ];
        
    }
}
