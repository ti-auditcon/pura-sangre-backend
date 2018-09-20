<?php

namespace App\Http\Controllers\API\Users;

use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /** [__construct description] */
    public function __construct()
    {
        // parent::__construct();
        // $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $users = User::all();
      return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // $campos = $request->all();
      // $campos['verified'] = User::USUARIO_NO_VERIFICADO;
      // $campos['verification_token'] = User::generarVerificationToken();
      // $user = User::create($campos);
      // return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
      return $this->showOne($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
      $user->update($request->all());
      return $this->showOne($user, 200);
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Users\User  $user
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(User $user)
    // {
    //   $user->delete();
    //   return $this->showOne($user, 200);
    // }
}
