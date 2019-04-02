<?php

namespace App\Http\Controllers\Users;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;
use App\Models\Clases\Reservation;
use App\Models\Plans\PlanUser;
use App\Models\Users\Emergency;
use App\Models\Users\User;
use App\Notifications\NewUser;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Session;

/**
 * [UserController description]
 */
class UserController extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        $this->middleware('can:view,user')->only('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('users.index')->with('users', $users);
    }


    public function export() 
    {
        return Excel::download(new UsersExport, toDay()->format('d-m-Y').'_usuarios.xls');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * [store description]
     * @param  UserRequest $request [description]
     * @param  User        $user    [description]
     * @return [type]               [description]
     */
    public function store(UserRequest $request, User $user)
    {
        $user = User::create(array_merge($request->all(), [
            'password' => bcrypt('purasangre'),
            'avatar' => url('img/default_user.png'),
        ]));
        $emergency = Emergency::create(array_merge($request->all(), [
            'user_id' => $user->id
        ]));
        if ($user->save()) {
            Session::flash('success','El usuario ha sido creado correctamente');
            return view('users.show')->with('user', $user);
        }else {
            return Redirect::back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Users\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit')->with('user', $user);
    }

    /**
     * [update description]
     * @param  UserRequest $request [description]
     * @param  User        $user    [description]
     * @return [type]               [description]
     */
    public function update(UserRequest $request, User $user)
    {
        if ($request->image) {
            request()->file('image')->storeAs('public/users', $user->id.$user->first_name.'.jpg');
        }
        $user->update(array_merge($request->all(), [
            'birthdate' => $request->birthdate,
            'since' => $request->since,
            'avatar' => url('/').'/storage/users/'.$user->id.$user->first_name.'.jpg',
        ]));

        if ($user->emergency) {
            $user->emergency()->update([
               'contact_name' => $request->contact_name,
               'contact_phone' => $request->contact_phone,
            ]);
        }else{
            Emergency::create([
                'user_id' => $user->id,
                'contact_name' => $request->contact_name,
                'contact_phone' => $request->contact_phone,
            ]);
        }

        Session::flash('success', 'Los datos del usuario han sido actualizados');
        return redirect('/users/'.$user->id)->with('user', $user);
    }

    /**
     * [image description]
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function image(Request $request, User $user)
    {
        if ($request->hasFile('image')) {
            request()->file('image')->storeAs('public/users', $user->id.$user->first_name.'.jpg');
            $user->avatar = url('/').'/storage/users/'.$user->id.$user->first_name.'.jpg';
            $user->save();
            return response()->json(['success' =>'imagen subida correctamente'], 200);
        }
        else {
            return response()->json(['error' =>'no hay imagen'], 400);
        }
    }

    /**
     * [destroy description]
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();
        return redirect('/users')->with('success', 'El usuario ha sido borrado correctamente');
    }

    /**
     * [updateAvatar description]
     * @return [type] [description]
     */
    public function updateAvatar()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->avatar = url('/').'/storage/users/u ('.rand ( 1, 54 ).').jpg';
            $user->save();
        }
        return 'listoco';
    }

    public function userinfo(User $user, planuser $plan)
    {
        $response = [
            'user_name' => $user->first_name. ' ' .$user->last_name,
            'plan' => $plan->plan->plan,
            'dates' => $plan->start_date->format('d/m/Y'). ' al ' .$plan->finish_date->format('d/m/Y'),
            'amount' => $plan->bill ? '$ '.number_format($plan->bill->amount, $decimal = 0, '.', '.') : 'No aplica',
            'left_clases' => $plan->counter ? : '',
            'status_plan' => $plan->plan_status->plan_status,
            'observations' => $plan->observations ? : ''
        ];
        echo json_encode($response);
    }

    public function putIdPlan()
    {
        foreach (Reservation::all() as $reserv) {
            if ( $reserv->plan_user_id == null) {
                $fecha_clase = $reserv->clase->date;
                $plan = PlanUser::where('start_date', '<=', $fecha_clase)
                                ->where('finish_date', '>=', $fecha_clase)
                                ->where('user_id', $reserv->user_id)
                                ->first();
                if ($plan) {
                    $reserv->update(['plan_user_id' => $plan->id]);
                    $plan->counter -= 1;
                    $plan->save();
                }
            }
        }
        return 'All successfully updated!!';
    }
}
