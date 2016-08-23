<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
//use App\Models\SocialNetwork;
//use Mockery\CountValidator\Exception;
use App\Http\Controllers\Controller;
use Socialite;
use App\Services\SocialNetworkServices;
use Auth;
class SocialNetworkController extends Controller
{
    // protected $socialNetwork;
    // public function __construct(SocialNetwork $socialNetwork)
    // {
    //     $this->socialNetwork = $socialNetwork;
    // }
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function callback(SocialNetworkServices $service, $provider)
    {
        $user = $service->createOrGetUser(Socialite::driver($provider));
        Auth::login($user);

        return redirect()->to('/');
        // try {
        //     $user = $this->socialNetwork->createOrGetUser(Socialite::driver($network)->user(), $network);
        //     auth()->login($user);
        // } catch (Exception $e) {
        //     throw new Exception($e->getMessage());
        // }
        // return redirect()->to('/');
    }
}