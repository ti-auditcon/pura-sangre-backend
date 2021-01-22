<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Web\Mail\ContactMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /**
     *   
     */
    public function sendEmail(Request $request)
    {
        dd('hola hola sendEmail');
        if ($this->thereErrors($request)) {
            return response()->json($this->thereErrors($request));
        }

        Mail::to("contacto@purasangrecrossfit.cl")->send(new ContactMail($request));

        if (Mail::failures()) {
            return response()->json(['warning' => 'Lo siento en estos momentos estamos teniendo problemas recibiendo tu informacion, por favor intenta mas tarde']);
        }

        return response()->json(['success' => 'Hemos recibido tu mensaje correctamente, te responderemos lo antes posible :)']);
    }

    /**
     *  
     */
    public function thereErrors($request)
    {
        if (!$request->ajax()) {
            return ['error' => 'La solicitud debe ser ajax'];
        }

        // check RECAPTCHA validation

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mensaje' => 'required|max:700'
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        return false; // there are no errors
    }
}
