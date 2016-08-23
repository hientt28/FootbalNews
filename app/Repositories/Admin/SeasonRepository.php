<?php

namespace App\Repositories\Admin;

use App\Models\LeagueSeason;
use App\Models\Season;
use App\Repositories\BaseRepository;
use DB;

class SeasonRepository extends BaseRepository
{
    public function __construct(Season $season)
    {
        $this->model = $season;
    }

    public function searchByStart($input)
    {
        $results = Season::orderBy('id', 'DESC');
        if (!empty($input)) {
            $results = $results->where("start", "LIKE", "%{$input}%")
                ->paginate(config('common.pagination.per_page'))
                ->appends(['search' => $input]);
        } else {
            $results = $results->paginate(config('common.pagination.per_page'));
        }

        return $results;
    }
    
    public function store($input, $leagues)
    {
        try {
            DB::beginTransaction();
            $season = Season::create($input);
            $leagueSeasons = [];

            foreach ($leagues as $league) {
                $leagueSeason['league_id'] = $league;
                $leagueSeason['season_id'] = $season->id;

                $leagueSeasons[] = $leagueSeason;
            }

            LeagueSeason::insert($leagueSeasons);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($input, $leagues, $id)
    {
        try {
            DB::beginTransaction();
            Season::where('id', $id)->update($input);
            LeagueSeason::where('season_id', $id)->delete();
            $leagueSeasons = [];

            foreach ($leagues as $league) {
                $leagueSeason['league_id'] = $league;
                $leagueSeason['season_id'] = $id;

                $leagueSeasons[] = $leagueSeason;
            }

            LeagueSeason::insert($leagueSeasons);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            abort(500);
        }
    }

    public function deleteById($id)
    {
        try {
            DB::beginTransaction();
            Season::destroy($id);
            LeagueSeason::where('season_id', $id)->delete();
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
            Season::destroy($ids['id']);
            LeagueSeason::whereIn('season_id', $ids['id'])->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            abort(500);
        }
    }
}
