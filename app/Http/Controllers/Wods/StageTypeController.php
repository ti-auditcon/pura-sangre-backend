<?php

namespace App\Http\Controllers\Wods;

use Illuminate\Http\Request;
use App\Models\Wods\StageType;
use App\Models\Clases\ClaseType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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

    /**
     * Add a new Stage to a Clase Type
     *
     * @return  \Illuminate\Http\Response
     */
    public function addStage(ClaseType $clase_type, Request $request)
    {
        Cache::forget("clases_types_{$clase_type->id}_stages_types");

        StageType::create([
            'stage_type' => $request->stage_type,
            'clase_type_id' => $clase_type->id
        ]);

        return response()->json(['success' => 'Etapa creada correctamente'], 201);
    }

    /**
     * Update an specific Stage Type
     *
     * @param   StageType  $stage_type
     * @param   Request    $request
     *
     * @return  json
     */
    public function update(StageType $stagesType, Request $request)
    {
        // If user change featured Stage
        if (isset($request->featured)) {
            Cache::forget("clases_types_{$stagesType->clase_type_id}_stages_types");

            $all_stages_type = StageType::where('clase_type_id', $stagesType->clase_type_id)->get();

            foreach ($all_stages_type as $stage) {
                $stage->update([
                    'featured' => $stagesType->id === $stage->id ? 1 : 0
                ]);
            }

            return response()->json(['success' => 'Etapa actualizada correctamente'], 200);
        }

        // If user change Stage Name
        $stagesType->update(['stage_type' => $request->stage_type]);
        Cache::forget("clases_types_{$stagesType->clase_type_id}_stages_types");
        
        return response()->json(['success' => 'Etapa actualizada correctamente'], 200);
    }
    
    /**
     * Delete Stage Type
     *
     * @return  json
     */
    public function destroy(StageType $stagesType)
    {
        Cache::forget("clases_types_{$stagesType->clase_type_id}_stages_types");

        $stagesType->stages()->delete();
        $stagesType->delete();
        
        return response()->json(['success' => 'Etapa eliminada correctamente'], 200);
    }
}
