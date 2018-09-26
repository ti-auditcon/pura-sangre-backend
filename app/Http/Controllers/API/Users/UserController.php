<?php

namespace App\Http\Controllers\API\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Auth;

class UserController extends Controller
{
  public function profile()
  {
      $user = Auth::user();
      return response()->json(compact('user'), 200);
  }
}
