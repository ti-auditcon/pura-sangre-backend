<?php

namespace App\Http\Controllers\Messages;

use Session;
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use App\Models\Users\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        // $this->Notification = new Notification;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('messages.notifications')->with('users', $users);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $users = User::whereIn('id', $request->to)->get();
        foreach ($users as $user) {
            $this->notification($user->fcm_token, $request->get('subject'), $request->get('text'));
        }
        Session::flash('success','Notificación enviada correctamente');
        return redirect()->route('messages.notifications');
    }

    public function notification($token, $title, $body)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token=$token;

        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => true,
        ];
        
        $extraNotificationData = ["message" => $notification, "moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=AAAAEWU-ai4:APA91bFCm4Yxb9Hh4m8te_RCrvk8HY_IaR9LfXUGQcuClcFs5Fy6a7d4irPoSbcIi48ei6kNnvodQCUua1Mb8h9QKEFtusbeCAcPpEAwSXxbKIjyrKDl3Ncm_tTFfnoQmqT9ZCD2hPSH',
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


        return true;
    }

}
