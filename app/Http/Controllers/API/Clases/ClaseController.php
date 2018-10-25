<?php

namespace App\Http\Controllers\Api\Clases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Clases\Clase;
use Session;

class ClaseController extends Controller
{
  public function index(Request $request)
  {
      
      $clases =  Clase::where('clase_type_id',Session::get('clases-type-id'))->get();
      return response()->json(compact('clases'), 200);
  }
}
