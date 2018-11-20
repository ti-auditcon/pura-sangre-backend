<?php

namespace App\Http\Controllers\Users;

use Session;
use App\Models\Users\User;
use App\Models\Users\Emergency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;

/**
 * [UserController description]
 */
class UserController extends Controller
{
    public function __construct()
    {
      // parent::__construct();
      $this->middleware('can:view,user')->only('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $users = User::all();
      return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * [store description]
     * @param  UserRequest $request [description]
     * @param  User        $user    [description]
     * @return [type]               [description]
     */
    public function store(UserRequest $request, User $user)
    {
      $emergency = Emergency::create($request->all());
      $user = User::create(array_merge($request->all(), [
        'password' => bcrypt('purasangre')]));
      Session::flash('success','El usuario ha sido creado correctamente');
      return view('users.show')->with('user', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $plan_users = $user->plan_users()->orderBy('created_at','desc')->orderBy('plan_status_id', 'ASC')->get();
        return view('users.show')->with('user', $user)->with('plan_users', $plan_users);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
      return view('users.edit')->with('user', $user);
    }

    /**
     * [update description]
     * @param  UserRequest $request [description]
     * @param  User        $user    [description]
     * @return [type]               [description]
     */
    public function update(UserRequest $request, User $user)
    {
        if ($request->image) {
            request()->file('image')->storeAs('public/users', $user->id.$user->first_name.'.jpg');
            $user->avatar = url('/').'/storage/users/'.$user->id.$user->first_name.'.jpg';
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        Session::flash('success','Los datos del usuario han sido actualizados');
        return view('users.show')->with('user', $user);
    }

    /**
     * [destroy description]
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function destroy(Request $request, User $user)
    {
      $user->delete();
      return redirect('/users')->with('success', 'El usuario ha sido borrado correctamente');
    }
}
