<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Repositories\Admin\LeagueRepository;
use Illuminate\Http\Request;
use Cloudder;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LeagueController extends Controller
{
    protected $leagueRepository;

    public function __construct(LeagueRepository $leagueRepository)
    {
        $this->leagueRepository = $leagueRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $key = $request->input('search');
        $results = $this->leagueRepository->searchByName($key);

        return view('admin.leagues.index', ['listLeagues' => $results]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $countries = Country::lists('name', 'id')->all();
        } catch (\Exception $e) {
            return redirect(route('admin.leagues.index'))->with('errors', $e->getMessage());
        }

        return view('admin.leagues.create', ['countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $inputs = $request->only(['name', 'country_id', 'description']);

            if ($request->hasFile('logo')) {
                $fileName = $request->logo;
                Cloudder::upload($fileName, config('common.path_cloud_league') . $request->name);
                $inputs['logo'] = Cloudder::getResult()['url'];
            }
            $this->leagueRepository->create($inputs);
        } catch (\Exception $e) {
            return redirect()->back()->with('errors', $e->getMessage())->withInput();
        }

        return redirect(route('admin.leagues.index'))->with('message', trans('message.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $league = $this->leagueRepository->find($id);
            $leagueSeasons = $league->leagueSeasons;
        } catch (\Exception $e) {
            return redirect(route('admin.leagues.index'))->with('errors', $e->getMessage());
        }

        return view('admin.leagues.detail', ['league' => $league, 'leagueSeasons' => $leagueSeasons]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $countries = Country::lists('name', 'id')->all();
            $result = $this->leagueRepository->find($id);
        } catch (\Exception $e) {
            return redirect(route('admin.leagues.index'))->with('errors', $e->getMessage());
        }

        return view('admin.leagues.edit', ['league' => $result, 'countries' => $countries]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $inputs = $request->only(['name', 'country_id', 'description']);

            if ($request->hasFile('logo')) {
                $fileName = $request->logo;
                Cloudder::upload($fileName, config('common.path_cloud_league') . $request->name);
                $inputs['logo'] = Cloudder::getResult()['url'];
            }
            $this->leagueRepository->updateById($inputs, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('errors', $e->getMessage())->withInput();
        }

        return redirect(route('admin.leagues.index'))->with('message', trans('message.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->leagueRepository->deleteById($id);
        } catch (\Exception $e) {
            return redirect(route('admin.leagues.index'))->with('errors', $e->getMessage());
        }

        return redirect(route('admin.leagues.index'))->with('message', trans('message.delete_success'));
    }

    //    delete multi
    public function deleteMulti(Request $request)
    {
        $ids = $request->all();
        $this->leagueRepository->deleteMulti($ids);
        $results = $this->leagueRepository->paginate(config('common.pagination.per_page_league'));
        $view = view('admin.partials.list_league', ['listLeagues' => $results])->render();

        return \Response::json([
            'view' => $view,
        ]);
    }
}
