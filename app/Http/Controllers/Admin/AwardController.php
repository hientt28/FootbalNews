<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\AwardRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AwardController extends Controller
{
    protected $awardRepository;

    public function __construct(AwardRepository $awardRepository)
    {
        $this->awardRepository = $awardRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $key = $request->input('search');
        $results = $this->awardRepository->searchByName($key);

        return view('admin.awards.index', ['listAwards' => $results]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.awards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|max:255',
        ]);

        try {
            $inputs = $request->only(['description']);
            $this->awardRepository->create($inputs);
        } catch (\Exception $e) {
            return redirect()->back()->with('errors', $e->getMessage())->withInput();
        }

        return redirect(route('admin.awards.index'))->with('message', trans('message.create_success'));
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
            $award = $this->awardRepository->find($id);
            $bestPlayers = $award->bestPlayers;
        } catch (\Exception $e) {
            return redirect(route('admin.awards.index'))->with('errors', $e->getMessage());
        }

        return view('admin.awards.detail', ['award' => $award, 'bestPlayers' => $bestPlayers]);
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
            $result = $this->awardRepository->find($id);
        } catch (\Exception $e) {
            return redirect(route('admin.awards.index'))->with('errors', $e->getMessage());
        }

        return view('admin.awards.edit', ['award' => $result]);
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
        $this->validate($request, [
            'description' => 'required|max:255',
        ]);

        try {
            $inputs = $request->only(['description']);
            $this->awardRepository->updateById($inputs, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('errors', $e->getMessage())->withInput();
        }

        return redirect(route('admin.awards.index'))->with('message', trans('message.update_success'));
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
            $this->awardRepository->deleteById($id);
        } catch (\Exception $e) {
            return redirect(route('admin.awards.index'))->with('errors', $e->getMessage());
        }

        return redirect(route('admin.awards.index'))->with('message', trans('message.delete_success'));
    }

    //    delete multi
    public function deleteMulti(Request $request)
    {
        $ids = $request->all();
        $this->awardRepository->deleteMulti($ids);
        $results = $this->awardRepository->paginate(config('common.pagination.per_page'));
        $view = view('admin.partials.list_award', ['listAwards' => $results])->render();

        return \Response::json([
            'view' => $view,
        ]);
    }
}
