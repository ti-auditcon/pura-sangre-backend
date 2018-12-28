<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;
use App\Models\Users\Emergency;
use App\Models\Users\User;
use App\Notifications\NewUser;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
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
            'avatar' => url('/').'/storage/users/default.jpg'
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
                // open an image file
        // $img = Image::make($request->image)->resize(300, 300);
        // dd($img);
        // // insert a watermark
        // $img->insert('public/watermark.png');

            // (string) $image->encode()
        // // save image in desired format
        // $img->save('public/bar.jpg');
        if ($request->image) {
            request()->file('image')->storeAs('public/users', $user->id.$user->first_name.'.jpg');
            // Storage::putFile('public/users', $img);
            $user->avatar = url('/').'/storage/users/'.$user->id.$user->first_name.'.jpg';
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->birthdate = Carbon::parse($request->birthdate);
        $user->since = Carbon::parse($request->since);
        $user->phone = $request->phone;
        $user->save();
        Session::flash('success','Los datos del usuario han sido actualizados');
        return view('users.show')->with('user', $user);
    }

    public function image(Request $request, User $user)
    {
        if ($request->hasFile('image')) {
            request()->file('image')->storeAs('public/users', $user->id.$user->first_name.'.jpg');
            $user->avatar = url('/').'/storage/users/'.$user->id.$user->first_name.'.jpg';
            $user->save();
            return response()->json(['success' =>'imagen subida'], 200);
        }
        else {
            return response()->json(['error' =>'nooooooooooooooo'], 400);
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


    public function updateAvatar()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->avatar = url('/').'/storage/users/u ('.rand ( 1, 54 ).').jpg';
            $user->save();
        }
        return 'listoco';
    }
}
