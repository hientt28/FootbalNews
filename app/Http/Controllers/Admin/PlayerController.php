<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Country\CountryRepository;
use App\Repositories\Player\PlayerRepository;
use App\Repositories\Position\PositionRepository;
use App\Repositories\Team\TeamRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;

class PlayerController extends Controller
{
    private $playerRepository;
    private $positionRepository;
    private $countryRepository;
    private $teamRepository;

    public function __construct(PlayerRepository $playerRepository,
                                PositionRepository $positionRepository,
                                CountryRepository $countryRepository,
                                TeamRepository $teamRepository
    )
    {
        $this->playerRepository = $playerRepository;
        $this->positionRepository = $positionRepository;
        $this->countryRepository = $countryRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = $this->playerRepository->paginate(config('common.paginate'));

        return view('admin.player.index', ['players' => $players]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $position = $this->positionRepository->lists('name', 'id');
        $country = $this->countryRepository->lists('name', 'id');
        $teams = $this->teamRepository->lists('name', 'id');
        return view('admin.player.create', compact('position', 'country', 'teams'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $player= $request->only('name', 'birthday');
        $player['country_id'] = $request->country_id;
        $player['position_id'] = $request->position_id;
        $player['team_id'] = $request->team_id;

        $data = $this->playerRepository->create($player);
        if (!$data) {
            return redirect()->route('admin.players.index')
                ->withErrors(['message' => trans('team.not_found')]);
        }
        return redirect()->route('admin.players.index')->withSuccess(trans('session.player_create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $players = $this->playerRepository->find($id);
        return view('admin.player.show', compact('players'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $players = $this->playerRepository->find($id);
        $position = $this->positionRepository->lists('name', 'id');
        $country = $this->countryRepository->lists('name', 'id');
        $teams = $this->teamRepository->lists('name', 'id');
        return view('admin.player.edit', compact('position', 'country', 'teams', 'players'));
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
        $player= $request->only('name', 'birthday');
        $player['country_id'] = $request->country_id;
        $player['position_id'] = $request->position_id;
        $player['team_id'] = $request->team_id;

        $data = $this->playerRepository->updateById($player, $id);
        if (!$data) {
            return redirect()->route('admin.players.index')
                ->withErrors(['message' => trans('team.not_found')]);
        }
        return redirect()->route('admin.players.index')->withSuccess(trans('session.player_update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $this->playerRepository->deleteById($id);

        } catch (\Exception $e) {

            return redirect(route('admin.players.index'))->withError($e->getMessage());
        }

        return redirect(route('admin.players.index'))->withSucces(trans('message.delete_success'));
    }
}