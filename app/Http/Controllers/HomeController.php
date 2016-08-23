<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Repositories\Team\TeamRepository;
use Illuminate\Http\Request;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;
class HomeController extends Controller
{
    private $teamRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TeamRepository $teamRepository)
    {
        $this->middleware('auth');
        $this->teamRepository = $teamRepository;
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
        $teams = $this->teamRepository->groupByDate();
        $chartteams = Lava::DataTable();

        $chartteams->addDateColumn(trans('common.day_of_month'))
            ->addNumberColumn(trans('common.team'));

        foreach ($teams as $key_team => $value_team) {
            $chartteams->addRow([$key_team, count($value_team)]);
        }

        Lava::LineChart('Team', $chartteams);
        return view('admin.chart.index');
    }
}