<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Repositories\BaseRepositoryInterface;
use Validator;
use Exception;
use Cloudder;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(BaseRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('/');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        return view('user.show', [
//            'user' => Auth::user()
//        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $user = $this->userRepository->find($id);
            if ($request->hasFile('avatar')) {
                $filename = $request->avatar;
                Cloudder::upload($filename, config('common.path_cloud_avatar')."$user->name");
                $user->avatar = Cloudder::getResult()['url'];
            }

            $user->name = $request->get('name', '');
            $user->address = $request->get('address', '');
            $user->phone = $request->get('phone', '');
            $user->email = $request->get('email', '');

            $user->save();
        } catch (Exception $ex) {
            return redirect()->route('users.edit')->withError($ex->getMessage());
        }

        return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
