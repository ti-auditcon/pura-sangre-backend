<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
  //Respuesta exitosa
  private function succesResponse($data, $code)
  {
    return response()->json($data, $code);
  }
}
