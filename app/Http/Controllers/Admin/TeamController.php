<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Country\CountryRepository;
use App\Repositories\Team\TeamRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cloudder;

class TeamController extends Controller
{

    private $teamRepository;
    private $countryRepository;
    public function __construct(TeamRepository $teamRepository, CountryRepository $countryRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->countryRepository = $countryRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = $this->teamRepository->paginate(config('common.paginate'));

        return view('admin.team.index', ['teams' => $teams]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $country = $this->countryRepository->lists('name', 'id');
        return view('admin.team.create', compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $team = $request->only('name', 'description', 'country_id');

        if ($request->hasFile('logo')){
            $fileName = $request->logo;
            Cloudder::upload($fileName, config('common.path_cloud_team') . "$request->name");
            $team['logo'] = Cloudder::getResult()['url'];
        }
        $data = $this->teamRepository->create($team);
        if (!$data) {
            return redirect()->route('admin.teams.index')
                ->withErrors(['message' => trans('team.not_found')]);
        }
        return redirect()->route('admin.teams.index')->withSuccess(trans('session.team_create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = $this->teamRepository->find($id);
        return view('admin.team.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = $this->teamRepository->find($id);
        $country = $this->countryRepository->lists('name', 'id');

        return view('admin.team.edit', compact('team', 'country'));
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
        $team = $request->only('name', 'description', 'country_id');

        if ($request->hasFile('logo')){
            $fileName = $request->logo;
            Cloudder::upload($fileName, config('common.path_cloud_team') . "$request->name");
            $team['logo'] = Cloudder::getResult()['url'];
        }

        $data = $this->teamRepository->updateById($team, $id);

        if (!$data) {
            return redirect()->route('admin.team.index')
                ->withErrors(['message' => trans('team.not_found')]);
        }

        return redirect()->route('admin.teams.index')->withSuccess(trans('session.team_update_success'));
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

            $this->teamRepository->deleteById($id);

        } catch (\Exception $e) {

            return redirect(route('admin.teams.index'))->withError($e->getMessage());
        }

        return redirect(route('admin.teams.index'))->withSucces(trans('message.delete_success'));
    }
}