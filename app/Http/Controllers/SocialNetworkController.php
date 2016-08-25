<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use App\Services\SocialNetworkServices;
use Auth;

class SocialNetworkController extends Controller
{

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function callback(SocialNetworkServices $service, $provider)
    {
        $user = $service->createOrGetUser(Socialite::driver($provider));
        Auth::login($user);
        return redirect()->to('/');
    }
}