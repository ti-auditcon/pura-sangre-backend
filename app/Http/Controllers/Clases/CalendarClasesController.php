<?php

namespace App\Http\Controllers\Clases;

use App\Http\Controllers\Controller;
use App\Models\Clases\Clase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarClasesController extends Controller
{
	/**
	 * Delete all clases for a given day
	 * @return [type] [description]
	 */
	public function destroy()
	{
		$date_converted = Carbon::parse(request('date'));

		$classes_chosen_date = Clase::where('date', $date_converted)->get();

		foreach ($classes_chosen_date as $clase) {
			$clase->delete();
		}

		return response()->json([

			'success' => 'Clases del día '. request('date') .' eliminadas correctamente'
		
		]);
	}
}
