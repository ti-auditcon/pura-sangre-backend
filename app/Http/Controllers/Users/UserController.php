<?php

namespace App\Http\Controllers\Users;

use Session;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;

/**
 * [UserController description]
 */
class UserController extends Controller
{
    /**
     * [__construct description]
     */
    // public function __construct()
    // {
    //     parent::__construct();
    // }

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
      // $emergency = Emergency::create($request->all());
      $user = User::create(array_add($request->all(), 'password', bcrypt('purasangre')));
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
      return view('users.show')->with('user', $user);
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
      $user->update($request->all());
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
