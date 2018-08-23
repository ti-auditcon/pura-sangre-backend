<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;

/**
 * [ApiController description]
 */
class ApiController extends Controller
{
  use ApiResponser;
	/**
	 * [__construct description]
	 */
    public function __construct()
    {
    	$this->middleware('auth:api');
    }
}
