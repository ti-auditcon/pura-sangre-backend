<?php

namespace App\Http\Controllers\API\Clases;

use App\Models\Clases\Clase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ClaseController extends Controller
{
    public function index(Request $request)
    {
        $clases =  Clase::where('clase_type_id', Session::get('clases-type-id'))->get();

        return response()->json(compact('clases'), 200);
    }
}
