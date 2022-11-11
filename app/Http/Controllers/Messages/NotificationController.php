<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\Controller;
use App\Jobs\SendPushNotification;
use App\Models\Users\Notification;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::orderByDesc('trigger_at')
                                     ->get(['id', 'title', 'body', 'sended', 'trigger_at']);

        return view('messages.notifications', ['notifications' => $notifications]);
    }

    /**
     * [store description]
     *
     * @param  Request $r [description]
     * @return [type]     [description]
     */
    public function store(Request $request)
    {
        $trigger_date = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

        $not = Notification::create([
            'users' => implode($request->to),
            'title' => $request->title,
            'body' => $request->body,
            'trigger_at' => $trigger_date
        ]);

        Session::flash('success', 'correcto');

        return redirect()->route('messages.notifications');
    }

    /**
     * [destroy description]
     *
     * @param  Notification $notification [description]
     * @return [type]                     [description]
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return back()->with('succes', 'Notificación eliminada correctamente');
    }

    /**
     * sendOnePush
     *
     * @return  returnType
     */
    public function sendOnePush($userId)
    {
        $user = User::find((int) $userId);
        $title = '📣 Hoy día de Trepa, ⚠ recuerda tus medias largas!!🔥';
        $body = '📣 Mensaje de prueba!!🔥🔥🔥🔥🔥';

        $fcmUrl = env('FIREBASE_CLOUD_MESSAGING_URL', 'https://fcm.googleapis.com/fcm/send');

        $notification = ['title' => $title, 'body' => $body, 'sound' => true];

        $fcmNotification = [
            'to' => $user->fcm_token, //single token
            'notification' => $notification,
            'data' => $notification
        ];

        $headers = [
            'Authorization: key=' . env('FIREBASE_AUTHORIZATION_KEY', 'AAAAyEVqUCs:APA91bE77nkMYX2gfQmz9pA813fWzqfslJWYK6cLUUie9uwechvjAE6wler6W9oy-MMMZPsXY6v5KmlLyTGfkQ-PB0tdO-Dn0yGeqeU6NaQTL7XhtOG-7PkwHJv3-NoLxjqHooIvLCzr'),
            'Content-Type: application/json'
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $fcmUrl);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

        $result = curl_exec($ch);

        curl_close($ch);

        return 'Push Enviado - ' . now()->format('d-m-Y H:i:s');
    }
}

    // public function notification($token, $title, $body)
    // {
    //     $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    //     $token = $token;

    //     $notification = [
    //         'title' => $title,
    //         'body' => $body,
    //         'sound' => true,
    //     ];

    //     $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

    //     $fcmNotification = [
    //         //'registration_ids' => $tokenList, //multple token array
    //         'to'        => $token, //single token
    //         'notification' => $notification,
    //         'data' => $extraNotificationData
    //     ];

    //     $headers = [
    //         'Authorization: key=AAAAEWU-ai4:APA91bFCm4Yxb9Hh4m8te_RCrvk8HY_IaR9LfXUGQcuClcFs5Fy6a7d4irPoSbcIi48ei6kNnvodQCUua1Mb8h9QKEFtusbeCAcPpEAwSXxbKIjyrKDl3Ncm_tTFfnoQmqT9ZCD2hPSH',
    //         'Content-Type: application/json'
    //     ];


    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL,$fcmUrl);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
    //     $result = curl_exec($ch);
    //     curl_close($ch);

    //     return true;
    // }

