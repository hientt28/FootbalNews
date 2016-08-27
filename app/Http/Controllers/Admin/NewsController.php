<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepository;

class NewsController extends Controller
{
	protected $newsRepository;

	public function __construct(NewsRepository $newsRepository)
	{
		$this->newsRepository = $newsRepository;
	}

    public function index()
    {
    	$news = $this->newsRepository->all();

        if(app('request')->ajax()) {
            $matches = $this->newsRepository->match->all();
            $records = collect([]);
            if(count($matches) > 0) {
                $matches->map(function($match) use($records) {
                    $record = [];
                    $record['id'] = $match->id;
                    $record['home_id'] = $match->id;
                    $record['guest_id'] = $match->id;
                    $record['home_name'] = $this->newsRepository->team->find($match->home_id)->name;
                    $record['guest_name'] = $this->newsRepository->team->find($match->guest_id)->name;

                    $records->push($record);
                });
            }
            $datafields = [
                'id' => 'Id',
                'home_id' => 'Home Team',
                'guest_id' => 'Guest Team',
            ];

            return response()->json([
                'matches' => $records->all(),
                'datafields' => $datafields,
                'status' => 'OK'
            ]);
        }

    	return view('admin.news.index')->with(compact('news', 'matches'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->only('title', 'content', 'match_id');

        try {
            if ($request->hasFile('image')) {
                $fileName = $request->image;
                Cloudder::upload($fileName, config('common.path_cloud_news') . $request->name);
                $data['image'] = Cloudder::getResult()['url'];
                $this->newsRepository->create($data);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
