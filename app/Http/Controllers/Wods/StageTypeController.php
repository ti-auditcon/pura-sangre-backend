<?php

namespace App\Http\Controllers\Wods;

use Illuminate\Http\Request;
use App\Models\Wods\StageType;
use App\Http\Controllers\Controller;

class StageTypeController extends Controller
{
    /**
     * Get all the stages types of certain clase type
     *
     * @return \Illuminate\Http\Response
     */
    public function show($stage_type)
    {
        $stages = StageType::where('clase_type_id', $stage_type)
                       ->get(['id', 'stage_type', 'clase_type_id', 'featured']);

        return response()->json($stages);
    }
}
