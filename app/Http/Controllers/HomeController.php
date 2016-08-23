<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use App\Models\Notification;
use Illuminate\Http\Request;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       /* $this->middleware('auth');*/
    }
    /**
     * Show the application dashboardn .
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
    public function getTotalNotification() {
        $total = 0;   
        try {
            $total = Notification::count();
        } catch(Exception $e) {
            $total = 0;
        }

        return response()->json(['total' => $total, 'status' => 'OK']);
    }
}