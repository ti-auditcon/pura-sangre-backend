<?php

namespace App\Http\Controllers\Clases;

use Carbon\Carbon;
use App\Models\Clases\Clase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalendarClaseDeleteController extends Controller
{
	/**
	 * Handle the incoming request.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(Request $request)
	{
		$date_converted = Carbon::parse($request->date);

		$classes_chosen_date = Clase::where('date', '>=', $date_converted->format('Y-m-d 00:00:00'))
									->where('date', '<=', $date_converted->format('Y-m-d 23:59:59'))
									->where('clase_type_id', $request->type_clase)
									->get();

		foreach ($classes_chosen_date as $clase) {
			$clase->delete();
		}

		return response()->json([
			'success' => "Clases del día {$request->date} eliminadas correctamente"
		]);
	}
}
