<?php

namespace App\Http\Controllers\Messages;

use Redirect;
use Session;
use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $users = User::all();
        return view('messages.messages')->with('users', $users);
    }

    /**
     * [send description]
     * @return [type] [description]
     */
    public function send(Request $request)
    {
        $users = User::whereIn('id', $request->users_id)->get();
        foreach ($users as $user) {
            $mail = new \stdClass();
            $mail->subject = $request->subject;
            $mail->text = $request->text;
            $mail->user = $user->first_name;
            Mail::to($user->email)->send(new SendEmail($mail));
        }
        Session::flash('success', 'Correos enviados correctamente');
        return redirect()->back();
    }
}
     // Mail::send(['title' => $mail['subject'], 'content' => $mail['text'], 'to' => $user->email], function ($message)
     //        {
     //            // $message->to($user->email);
     //            // $message->attach($attach);
     //            // $message->subject($mail['subject']);
     //            // $message->text($mail['text']);
     //        });
     //    }