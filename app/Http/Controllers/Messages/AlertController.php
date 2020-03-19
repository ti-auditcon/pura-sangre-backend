<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\AlertRequest;
use App\Models\Users\Alert;
use Illuminate\Http\Request;
use Session;
use redirect;


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
     *  Store a newly created resource in storage.
     *
     *  @param  \Illuminate\Http\Request  $request
     *
     *  @return \Illuminate\Http\Response
     */
    public function store(AlertRequest $request)
    {
        Alert::create($request->all());

        return redirect('/alerts')->with('success','La alerta ha sido creada exitosamente');
    }

    /**
     *  Get JSON of All alerts
     *
     *  @return  json
     */
    public function alerts()
    {
        $alerts = Alert::orderByDesc('from')->get(['id', 'from', 'to', 'message']);

        return json_encode(['data' => $alerts]);
    }

    /**
     *  [destroy description]
     *
     *  @param   [type]  $id  [$id description]
     *
     *  @return  [type]       [return description]
     */
    public function destroy($id)
    {
        Alert::find($id)->delete();

        return response()->json(['done']);
    }
}
