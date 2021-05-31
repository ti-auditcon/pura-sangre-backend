<?php

namespace App\Http\Controllers\Users;

use Session;
use Redirect;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Models\Plans\PlanUser;
use App\Models\Users\Emergency;
use App\Models\Clases\Reservation;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Users\UserRequest;

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
        $status_users = User::CountStatusUsers()->get();

        return view('users.index', ['status_users' => $status_users]);
    }

    /**
     * [usersJson description]
     *
     * @return json
     */
    public function usersJson()
    {
        $users = User::allUsers()->get();

        return response()->json(['data' => $users]);
    }

    /**
     * [export description]
     *
     * @return Maatwebsite\Excel\Facades\Excel
     */
    public function export()
    {
        return Excel::download(
            new UsersExport, toDay()->format('d-m-Y') . '_usuarios.xls'
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->hasRole(1)) {
            return back();
        }

        return view('users.create');
    }

    /**
     * [store description]
     *
     * @param UserRequest $request [description]
     * @param User        $user    [description]
     *
     * @return Users show view
     */
    public function store(UserRequest $request, User $user)
    {
        $user = User::create($request->all());

        $auth_roles = auth()->user(['id'])->roles()->orderBy('role_id')->pluck('id')->toArray();

        $emergency = Emergency::create(array_merge($request->all(),
            ['user_id' => $user->id]
        ));

        Session::flash('success', 'El usuario ha sido creado correctamente');

        return view(
            'users.show', [
                'user' => $user,
                'past_reservations' => $user->past_reservations(),
                'auth_roles' => $auth_roles
            ]
        );
    }

    /**
     * Show details of the user
     *
     * @param User $user
     *
     * @return User show view
     */
    public function show(User $user)
    {
        return view(
            'users.show', [
                'user' => $user,
                'past_reservations' => $user->past_reservations(),
                'auth_roles' => auth()->user(['id'])->roles()->orderBy('role_id')->pluck('id')->toArray()
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Users\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit')->with('user', $user);
    }

    /**
     * [update description]
     *
     * @param  UserRequest $request [description]
     * @param  User        $user    [description]
     *
     * @return [type]               [description]
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update(array_merge($request->all(), [
            'birthdate' => $request->birthdate,
            'since' => $request->since
        ]));

        $updateOrCreate = $user->emergency ? 'update' : 'create';

        $user->emergency()->$updateOrCreate([
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
        ]);

        Session::flash('success', 'Los datos del usuario han sido actualizados');

        return redirect("/users/{$user->id}")->with(compact('user'));
    }

    /**
     *  Reset to default value the specific user's password
     *
     *  @param   User  $user
     *
     *  @return  \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(User $user)
    {
        $dispatcher = User::getEventDispatcher();
        // disabling the events
        User::unsetEventDispatcher();
        // perform the operation
        $user->update(['password' => bcrypt('purasangre')]);
        // enabling the event dispatcher
        User::setEventDispatcher($dispatcher);

        return response()->json(
            ['success' => "Se ha restablecido la contraseña de {$user->first_name}, su contraseña ahora es: purasangre"],
            201
        );
    }

    /**
     *  [image description]
     *
     *  @param  Request $request [description]
     *  @param  User    $user    [description]
     *
     *  @return [type]           [description]
     */
    public function image(Request $request, User $user)
    {
        $avatar_name = md5(mt_rand());

        request()->file('avatar')->storeAs('public/users', "{$avatar_name}.jpg");

        $user->avatar = url('/') . '/storage/users/' . $avatar_name . '.jpg';

        $user->save();

        return response()->json(['success' => 'Sesion finalizada'], 200);
    }

    /**
     * [destroy description]
     *
     * @param  Request $request [description]
     * @param  User    $user    [description]
     *
     * @return [type]           [description]
     */
    public function destroy(Request $request, User $user)
    {
        $user->delete();

        return redirect('/users')->with('success', 'El usuario ha sido borrado correctamente');
    }

    /**
     * [updateAvatar description]
     *
     * @return [type] [description]
     */
    public function updateAvatar()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->avatar = url('/') . '/storage/users/u (' . rand(1, 54) . ').jpg';
            $user->save();
        }

        return 'listoco';
    }

    public function userinfo(User $user, planuser $plan)
    {
        $response = [
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'plan' => $plan->plan->plan,
            'dates' => $plan->start_date->format('d/m/Y') . ' al ' . $plan->finish_date->format('d/m/Y'),
            'amount' => $plan->bill ? '$ ' . number_format($plan->bill->amount, $decimal = 0, '.', '.') : 'No aplica',
            'left_clases' => $plan->counter ?: '',
            'status_plan' => $plan->plan_status->plan_status,
            'observations' => $plan->observations ?: '',
        ];

        echo json_encode($response);
    }

    public function putIdPlan()
    {
        foreach (Reservation::all() as $reserv) {
            if ($reserv->plan_user_id == null) {
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

    /**
     * [geolocations description]
     *
     * @return json
     */
    public function geolocations() {
        $users = User::whereNotNull('address')->get(['id', 'lat', 'lng']);

        return $users;
    }
}
