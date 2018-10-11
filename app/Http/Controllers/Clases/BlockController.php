<?php

namespace App\Http\Controllers\Clases;

use Session;
use Redirect;
use App\Models\Clases\Block;
use App\Models\Users\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Clases\BlockRequest;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //  dd(Role::coaches());
      $blocks = Block::where('clase_type_id',Session::get('clases-type'))->get()->toArray();

      return view('blocks.index')->with('blocks',json_encode($blocks));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlockRequest $request){
      //si es bloque multiple o unico
      if($request->repetition == 'multiple'){
        foreach ($request->day as $day) {
          $block = Block::create([
              'start'=>$request->start,
              'end'=>$request->end,
              'dow'=>$day,
              'clase_type_id'=>$request->clase_type_id
          ]);
          $block->plans()->sync($request->plans);
        }
        return Redirect::back();
      } else {
        $block = Block::create([
            'start'=>$request->start,
            'end'=>$request->end,
            'date'=>date("Y-d-m",strtotime($request->date)),//falta local
            'clase_type_id'=>$request->clase_type_id
        ]);
        $block->plans()->sync($request->plans);
        return Redirect::back();
      }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function show(Block $block)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function edit(Block $block)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clases\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Block $block)
    {
      $block->plans()->sync($request->plans);
      return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy(Block $block)
    {
        $block->plans()->detach();
        $block->delete();
        return Redirect::back();
    }

}
