<?php

namespace App\Repositories\Admin;

use App\Models\League;
use App\Models\LeagueSeason;
use App\Repositories\BaseRepository;
use DB;

class LeagueRepository extends BaseRepository
{
    public function __construct(League $league)
    {
        $this->model = $league;
    }

    public function searchByName($input)
    {
        $results = League::orderBy('id', 'DESC');
        if (!empty($input)) {
            $results = $results->where("name", "LIKE", "%{$input}%")
                ->paginate(config('common.pagination.per_page_league'))
                ->appends(['search' => $input]);
        } else {
            $results = $results->paginate(config('common.pagination.per_page_league'));
        }

        return $results;
    }

    public function deleteById($id)
    {
        try {
            DB::beginTransaction();
            League::destroy($id);
            LeagueSeason::where('league_id', $id)->delete();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            abort(500);
        }

        return true;
    }

    public function deleteMulti($ids)
    {
        try {
            DB::beginTransaction();
            League::destroy($ids['id']);
            LeagueSeason::whereIn('league_id', $ids['id'])->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            abort(500);
        }
    }
}
