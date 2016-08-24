<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Repositories\Post\PostRepository;
use App\Repositories\UserMatch\UserMatchRepository;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;
class HomeController extends Controller
{
    private $postRepository;
    private $userMatchRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PostRepository $postRepository, UserMatchRepository $userMatchRepository)
    {
        $this->middleware('auth');
        $this->postRepository = $postRepository;
        $this->userMatchRepository = $userMatchRepository;
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

        //PieChart of Products
        $user_matches = $this->userMatchRepository->chart();
        $dataUserMatches = Lava::DataTable();
        $dataUserMatches->addStringColumn(trans('common.price'))
                        ->addBooleanColumn(trans('common.percent'));
        $price = config('common.price');
        foreach ($user_matches as $key_user => $value_price) {
            foreach ($value_price as $key => $value) {
                $price += $value->price;
                $name = $value->user->name;
            }

            $dataUserMatches->addRow([$name, $price]);
            $price = config('common.price');
        }

        Lava::PieChart('UserMatch', $dataUserMatches, [
                'title'  => trans('common.price_of_user'),
                'is3D'   => true,
                'slices' => [
                            ['offset' => config('common.offset')],
                        ]
                ]);
        return view('admin.chart.index');
    }
}