<?php

namespace App\Http\Controllers\Clases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clases\BlockRequest;
use App\Models\Clases\Block;
use App\Models\Plans\Plan;
use Illuminate\Http\Request;
use Redirect;
use Session;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $blocks = app(Block::class)->claseTypesInSession();

        $plans = Plan::with('plan_period:id,period')
            ->where('plan_status_id', Plan::ACTIVE)
            ->get(['id', 'plan', 'plan_period_id']);

        return view('blocks.index', [
            'blocks' => json_encode($blocks), 
            'plans' => $plans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BlockRequest $request)
    {
        //si es bloque multiple o unico
        if ($request->repetition == 'multiple') {
            foreach ($request->day as $day) {
                $block = Block::create([
                    'start' => $request->start,
                    'end' => $request->end,
                    'dow' => $day,
                    'clase_type_id' => $request->clase_type_id,
                    'coach_id' => $request->coach_id,
                    'quota' => $request->quota,
                ]);
                $block->plans()->sync($request->plans);
            }
            return Redirect::back();
        } else {
            $block = Block::create([
                'start' => $request->start,
                'end' => $request->end,
                'date' => date("Y-m-d", strtotime($request->date)), //falta local
                'clase_type_id' => $request->clase_type_id,
                'coach_id' => $request->coach_id,
                'quota' => $request->quota,
            ]);
            $block->plans()->sync($request->plans);
            return Redirect::back();
        }
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
        $block->update([
            'quota'    => $request->quota,
            'coach_id' => $request->coach_id,
            'start'    => $request->start,
            'end'      => $request->end,
        ]);

        $block->plans()->sync($request->plans);

        return redirect()->route('blocks.index');
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
