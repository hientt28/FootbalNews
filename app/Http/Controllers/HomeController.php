<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Models\Post;
use App\Repositories\Post\PostRepository;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;
class HomeController extends Controller
{
    private $postRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->middleware('auth');
        $this->postRepository = $postRepository;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function welcome()
    {
        return view('welcome');
    }

    public function chart()
    {
        $posts = $this->postRepository->all();
        
        $chartteams = Lava::DataTable();

        $chartteams->addStringColumn(trans('common.new'))
            ->addNumberColumn(trans('common.comment'));

        foreach ($posts as $post) {
            $chartteams->addRow([$post->title, count($post->comments)]);
        }
        Lava::ColumnChart('Team', $chartteams);
        return view('admin.chart.index');
    }
}