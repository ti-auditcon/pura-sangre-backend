<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use App\Models\Users\Alert;
use Illuminate\Http\Request;
use Session;


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
        return redirect('/alerts')->with('success','La alerta ha sido creada exitosamente');
    }

    public function alerts()
    {
        $alerts = Alert::orderBy('from', 'desc')->get();
        return $alerts->map(function ($alert){
            return [
                'id' => $alert->id,
                'message' => $alert->message,
                'from' => date('d-m-Y', strtotime($alert->from)),
                'to' => date('d-m-Y', strtotime($alert->to)),
            ];
        });
    }

    public function destroy($id)
    {
        Alert::find($id)->delete();
        return response()->json(['done']);
    }
}
