<?php

namespace App\Http\Controllers\Messages;

use Session;
use App\Models\Users\Alert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('messages.alerts');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Alert::create($request->all());
        Session::flash('success','La alerta ha sido creada exitosamente');
        return view('messages.alerts');
    }
}
