<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\SocialNetwork;
use Mockery\CountValidator\Exception;
use Socialite;
class SocialNetworkController extends Controller
{
    protected $socialNetwork;
    public function __construct(SocialNetwork $socialNetwork)
    {
        $this->socialNetwork = $socialNetwork;
    }
    public function redirect($driver)
    {
        return Socialite::driver($driver)->redirect();
    }
    public function callback($network)
    {
        try {
            $user = $this->socialNetwork->createOrGetUser(Socialite::driver($network)->user(), $network);
            auth()->login($user);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return redirect()->to('/');
    }
}