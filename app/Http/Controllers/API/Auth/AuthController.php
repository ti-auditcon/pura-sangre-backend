<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Users\User;
use Validator;//por mientras

class AuthController extends Controller
{
  public function login(Request $request)
  {
      if (Auth::attempt($request->only('email', 'password'))) {
          $user = Auth::user();
          $token =  $user->createToken('FromApp')->accessToken;
          return response()->json([
              'token' => $token,
              'user' => $user
          ], 200);
      } else {
          return response()->json(['error' => 'Unauthorised'], 401);
      }
  }
}
