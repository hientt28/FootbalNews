<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\News\NewsRepository;
use Cloudder;

class NewsController extends Controller
{
	protected $newsRepository;

	public function __construct(NewsRepository $newsRepository)
	{
		$this->newsRepository = $newsRepository;
	}

    public function index()
    {
    	$news = \App\Models\News::with('comments')->get();
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
                'news' => $news,
                'datafields' => $datafields,
                'status' => 'OK'
            ]);
        }

    	return view('admin.news.index')->with(compact('news', 'matches'));
    }

    public function create()
    {
        $data = app('request')->all();
        $data['user_id'] = auth()->user()->id;
        $this->newsRepository->create($data);

        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function addComment()
    {
        $data = app('request')->all();
        $data['user_id'] = auth()->user()->id;
        $news = $this->newsRepository->find($data['news_id']);
        $news->comments()->create($data);

        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->only('title', 'content', 'match_id');
        $news = collect([]);
        try {
            if ($request->hasFile('image')) {
                $fileName = $request->image;
                Cloudder::upload($fileName, config('common.path_cloud_news') . $data['title']);
                $data['image'] = Cloudder::getResult()['url'];
            }
            $data['user_id'] = auth()->user()->id;

            $this->newsRepository->create($data);
            $news = $this->newsRepository->all();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return view('admin.news.index', ['news' => $news])->withSuccess('Create news successfully!');
    }
}
