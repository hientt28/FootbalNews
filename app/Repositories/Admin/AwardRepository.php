<?php

namespace App\Repositories\Admin;

use App\Models\Award;
use App\Models\BestPlayerOfLeague;
use App\Repositories\BaseRepository;
use DB;

class AwardRepository extends BaseRepository
{
    public function __construct(Award $award)
    {
        $this->model = $award;
    }

    public function searchByName($input)
    {
        $results = Award::orderBy('id', 'DESC');
        if (!empty($input)) {
            $results = $results->where("description", "LIKE", "%{$input}%")
                ->paginate(config('common.pagination.per_page'))
                ->appends(['search' => $input]);
        } else {
            $results = $results->paginate(config('common.pagination.per_page'));
        }

        return $results;
    }

    public function deleteById($id)
    {
        try {
            DB::beginTransaction();
            Award::destroy($id);
            BestPlayerOfLeague::where('award_id', $id)->delete();
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
            Award::destroy($ids['id']);
            BestPlayerOfLeague::whereIn('award_id', $ids['id'])->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            abort(500);
        }
    }
}
