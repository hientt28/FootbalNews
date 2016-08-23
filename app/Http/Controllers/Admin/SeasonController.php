<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\League;
use App\Models\LeagueSeason;
use App\Repositories\Admin\SeasonRepository;
use Illuminate\Http\Request;

class SeasonController extends Controller
{
    protected $seasonRepository;

    public function __construct(SeasonRepository $seasonRepository)
    {
        $this->seasonRepository = $seasonRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $key = $request->input('search');
        $results = $this->seasonRepository->searchByStart($key);

        return view('admin.seasons.index', ['listSeasons' => $results]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $leagues = League::all();
        } catch (\Exception $e) {
            return redirect(route('admin.seasons.index'))->with('errors', $e->getMessage());
        }
        
        return view('admin.seasons.create', ['leagues' => $leagues]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $inputs = $request->only(['start', 'end']);
            $leagues = $request->input('league');
           
            $this->seasonRepository->store($inputs, $leagues);
        } catch (\Exception $e) {
            return redirect()->back()->with('errors', $e->getMessage())->withInput();
        }

        return redirect(route('admin.seasons.index'))->with('message', trans('message.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $season = $this->seasonRepository->find($id);
            $leagueSeasons = $season->leagueSeasons;
        } catch (\Exception $e) {
            return redirect(route('admin.seasons.index'))->with('errors', $e->getMessage());
        }

        return view('admin.seasons.detail', ['season' => $season, 'leagueSeasons' => $leagueSeasons]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $leagues = League::all();
            $leagueSeasons = LeagueSeason::where('season_id', $id)->get();
            $result = $this->seasonRepository->find($id);
        } catch (\Exception $e) {
            return redirect(route('admin.seasons.index'))->with('errors', $e->getMessage());
        }

        return view('admin.seasons.edit', [
            'season' => $result,
            'leagues' => $leagues,
            'leagueSeasons' => $leagueSeasons
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
        try {
            $inputs = $request->only(['start', 'end']);
            $leagues = $request->input('league');
            $this->seasonRepository->update($inputs, $leagues, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('errors', $e->getMessage())->withInput();
        }

        return redirect(route('admin.seasons.index'))->with('message', trans('message.update_success'));
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
            $this->seasonRepository->deleteById($id);
        } catch (\Exception $e) {
            return redirect(route('admin.seasons.index'))->with('errors', $e->getMessage());
        }

        return redirect(route('admin.seasons.index'))->with('message', trans('message.delete_success'));
    }

    //    delete multi
    public function deleteMulti(Request $request)
    {
        $ids = $request->all();
        $this->seasonRepository->deleteMulti($ids);
        $results = $this->seasonRepository->paginate(config('common.pagination.per_page'));
        $view = view('admin.partials.list_season', ['listSeasons' => $results])->render();

        return \Response::json([
            'view' => $view,
        ]);
    }
}
