<?php

namespace App\Http\Controllers\Wods;

use Session;
use Illuminate\Http\Request;
use App\Models\Wods\Stage;
use App\Http\Controllers\Controller;

class StageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Stage $stage)
    {
        Session::flash('date', $request->date);
        for ($i=0; $i < 3; $i++) { 
            Stage::create([
                'name' => $request->name[$i],
                'description' => $request->description[$i]]);
        }
        return redirect()->route('clases.index')->with('success', 'El workout se ha creado correctamente'); 
    }
}
