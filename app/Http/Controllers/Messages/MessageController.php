<?php

namespace App\Http\Controllers\Messages;

use Session;
use Redirect;
use App\Mail\SendEmail;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Mail\SendEmailQueue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Requests\Messages\MessageRequest;

class MessageController extends Controller
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        return view('messages.messages');
    }

    public function usersJson()
    {
        $users = User::all();
        $json_data = array(
            "recordsTotal"  => intval($users->count()),
            "data"          => $users
        );
        echo json_encode($json_data);
    }

    /**
     * [send description]
     * @return [type] [description]
     */
    public function send(MessageRequest $request)
    {
        $errors = null;
        $users = User::whereIn('id', explode (",", $request->to[0]))->get();
        
        $mailable = count($users) > 18 ? SendEmailQueue::class : SendEmail::class;

        if ($request->image) {
            $random_name = str_shuffle(str_replace([' ', ':'], '', $request->subject . now()));

            request()->file('image')->storeAs('public/emails', $random_name . '.jpg');
        } 

        foreach ($users as $user) {
            $mail = new \stdClass();
            $mail->subject = $request->subject;
            $mail->text = $request->text;
            $mail->user = $user->first_name;
            $mail->image_url = $request->image ? url('/') . '/storage/emails/' . $random_name . '.jpg' : null;

            try{
                Mail::to($user->email)->send(new $mailable($mail, $user));
            } catch(\Exception $e) {
                \DB::table('errors')->insert([
                    'error' => $e,
                    'where' => 'email',
                    'created_at' => now(),
                ]);
                $errors += 1;
            }
        }

        if ($errors) {
            Session::flash('warning', 'Hay error(es) en ' . $errors . ' correo(s)');
            return redirect()->back();
        }

        if (count($users) > 18) {
            Session::flash('success', 'Parece que has enviado un correo masivo, dependiendo de la cantidad de personas es lo que tomará la entrega de cada uno de los correos');
            return redirect()->back();
        }
        
        Session::flash('success', 'Correos enviados correctamente');
        return redirect()->back();
    }

    protected function dispatchJobsFromDB(Schedule $schedule)
    {
        $schedule->command('queue:work');
    }
}