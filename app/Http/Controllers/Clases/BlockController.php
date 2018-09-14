<?php

namespace App\Http\Controllers\Clases;

use App\Models\Clases\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $blocks = Block::all()->toArray();

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
    public function store(Request $request)
    {

      foreach ($request->day as $day) {
        $block = Block::create(['start'=>$request->start,'end'=>$request->end,'dow'=>$day]);
        $block->plans()->sync($request->plans);
      }
      return Redirect::back();
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
      dd('hola');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy(Block $block)
    {
        //
    }

}
