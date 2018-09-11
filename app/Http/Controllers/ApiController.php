<?php

namespace App\Http\Controllers;

// use App\Traits\ApiResponser;
// use Illuminate\Http\Request;

/**
 * [ApiController description]
 */
class ApiController extends Controller
{
  // use ApiResponser;
	/**
	 * [__construct description]
	 */
    public function __construct()
    {
    	$this->middleware('auth:api');
    }
}
