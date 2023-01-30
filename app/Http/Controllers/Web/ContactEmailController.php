<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Web\Mail\ContactMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Web\ContactEmailRequest;

class ContactEmailController extends Controller
{
    /**
     * 
     */
    public function sendEmail(Request $request)
    {
        $this->makeValidation($request);
     
        if (!$this->reCaptchaFails(request('token'))) {
            return response()->json(['error' => 'No se ha podido verificar el reCaptcha de la pagina']);
        }

        // Mail::to("contacto@purasangrecrossfit.cl")->send(new ContactMail($request));

        // if (Mail::failures()) {
        //     return response()->json(['warning' => 'Lo siento en estos momentos estamos teniendo problemas recibiendo tu informacion, por favor intenta mas tarde']);
        // }

        return response()->json(['success' => 'Hemos recibido tu mensaje correctamente, te responderemos lo antes posible :)']);
    }

    public function makeValidation($request)
    {
        return $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|max:700'
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo debe ser valido.',
            'message.required' => 'El motivo del mensaje es obligatorio.',
        ]);
    }

    /**
     * methodDescription
     *
     * @return  returnType
     */
    public function reCaptchaFails($captcha)
    {
        $secret = '6LdriT0aAAAAAApTzSkBAz6yd6I9DQecuxj_itQ6';

        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha = file_get_contents("{$recaptcha_url}?secret={$secret}&response={$captcha}");
        $recaptcha = json_decode($recaptcha);

        return $recaptcha->success;
    }
}
