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

    	return view('admin.news.index')->with(compact('news'));
    }
}
