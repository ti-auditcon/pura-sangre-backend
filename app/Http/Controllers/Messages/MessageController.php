<?php

namespace App\Http\Controllers\Messages;

use Session;
use Redirect;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendEmail;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

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
        $exist_user = \DB::table('password_resets')->whereEmail($user->email)->first();
        // dd($exist_user);
        $counter = 0;
            if (!$exist_user) {
                $token = str_random(64);
                \DB::table('password_resets')->insert([
                    'email' => $user->email, 
                    'token' => Hash::make($token),
                ]);
                $mail = new \stdClass();
                $mail->subject = $request->subject;
                $mail->text = $request->text;
                $mail->user = $user->first_name;
                Mail::to($user->email)->send(new SendEmail($mail, $user, $token));
            }else{
                $counter += 1;
            }
        }
        Session::flash('success', 'Correos enviados correctamente');
        Session::flash('success', 'N° de personas no enviadas: '.$counter);
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